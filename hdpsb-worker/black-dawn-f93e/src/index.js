export default {
  async fetch(request, env, ctx) {
    const url = new URL(request.url);
    const path = url.pathname;
    const action = url.searchParams.get("action");

    const headers = {
      "Content-Type": "application/json",
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, OPTIONS",
      "Access-Control-Allow-Headers": "Content-Type, Authorization",
    };

    if (request.method === "OPTIONS") {
      return new Response(null, { headers });
    }

    try {
      // GET /?action=data
      if (action === "data") {
        const { results } = await env.DB.prepare("SELECT * FROM tiket ORDER BY _rowIndex ASC").all();
        const lastIndex = results.length > 0 ? results[results.length - 1]._rowIndex : 1;
        return new Response(JSON.stringify({ rows: results, lastIndex }), { headers });
      }

      // GET /?action=new&lastIndex=X
      if (action === "new") {
        const lastIndex = parseInt(url.searchParams.get("lastIndex") || "0");
        const { results } = await env.DB.prepare("SELECT * FROM tiket WHERE _rowIndex > ? ORDER BY _rowIndex ASC").bind(lastIndex).all();
        const newLastIndex = results.length > 0 ? results[results.length - 1]._rowIndex : lastIndex;
        return new Response(JSON.stringify({ hasNew: results.length > 0, rows: results, lastIndex: newLastIndex }), { headers });
      }

      // GET /?action=klaim
      if (action === "klaim") {
        const rowIndex = parseInt(url.searchParams.get("rowIndex"));
        const pic = url.searchParams.get("pic");

        if (pic === "clear") {
          await env.DB.prepare("UPDATE tiket SET klaimedBy = NULL WHERE _rowIndex = ?").bind(rowIndex).run();
          return new Response(JSON.stringify({ ok: true }), { headers });
        }

        if (pic === "__check__") {
          const row = await env.DB.prepare("SELECT klaimedBy FROM tiket WHERE _rowIndex = ?").bind(rowIndex).first();
          return new Response(JSON.stringify({ klaimedBy: row?.klaimedBy || null }), { headers });
        }

        const row = await env.DB.prepare("SELECT klaimedBy FROM tiket WHERE _rowIndex = ?").bind(rowIndex).first();
        if (row?.klaimedBy && row.klaimedBy !== pic) {
          return new Response(JSON.stringify({ ok: false, klaimedBy: row.klaimedBy }), { headers });
        }

        await env.DB.prepare("UPDATE tiket SET klaimedBy = ? WHERE _rowIndex = ?").bind(pic, rowIndex).run();
        return new Response(JSON.stringify({ ok: true }), { headers });
      }

      // GET /?action=clearDb
      if (action === "clearDb") {
        const adminKey = url.searchParams.get("adminKey");
        if (adminKey !== "vanzndt28") {
          return new Response(JSON.stringify({ ok: false, msg: "Unauthorized" }), { status: 401, headers });
        }
        await env.DB.prepare("DELETE FROM tiket").run();
        await env.DB.prepare("DELETE FROM sqlite_sequence WHERE name='tiket'").run();
        return new Response(JSON.stringify({ ok: true }), { headers });
      }

      // POST /kirim
      if (path === "/kirim" && request.method === "POST") {
        const body = await request.json();
        await env.DB.prepare("UPDATE tiket SET statusKey = 'done', balasan = ?, pic = ? WHERE _rowIndex = ?")
          .bind(body.balasan, body.pic, body.rowIndex).run();
        return new Response(JSON.stringify({ ok: true }), { headers });
      }

      return new Response(JSON.stringify({ error: "Not found" }), { status: 404, headers });

    } catch (e) {
      return new Response(JSON.stringify({ error: e.message }), { status: 500, headers });
    }
  },
};
