const BOT_TOKEN = "8769697721:AAGbPs56r92VHVXITFYLpnrBvh7oZgJm8d0";

export default {
  async fetch(request, env, ctx) {
    const corsHeaders = {
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "GET, POST, OPTIONS",
      "Access-Control-Allow-Headers": "Content-Type, Authorization",
      "Access-Control-Max-Age": "86400"
    };

    if (request.method === "OPTIONS") {
      return new Response(null, { status: 204, headers: corsHeaders });
    }

    try {
      const response = await handleRequest(request, env, corsHeaders);
      const newHeaders = new Headers(response.headers);
      newHeaders.set("Access-Control-Allow-Origin", "*");
      return new Response(response.body, { status: response.status, headers: newHeaders });
    } catch (err) {
      return new Response(JSON.stringify({ error: err.message }), {
        status: 500,
        headers: { ...corsHeaders, "Content-Type": "application/json" }
      });
    }
  }
};

async function handleRequest(request, env, corsHeaders) {
  const url = new URL(request.url);
  if (request.method === "POST" && url.pathname === "/webhook") {
    return handleWebhook(env, await request.json());
  }
  if (request.method === "POST" && url.pathname === "/kirim") {
    return handleKirim(env, await request.json(), corsHeaders);
  }
  const action = url.searchParams.get("action") || "";
  if (action === "data")         return handleGetData(env, corsHeaders);
  if (action === "new")          return handleGetNew(env, url, corsHeaders);
  if (action === "klaim")        return handleKlaim(url, env, corsHeaders);
  if (action === "klaimStatus")  return handleKlaimStatus(url, env, corsHeaders);
  if (action === "clearDb")      return handleClearDb(url, env, corsHeaders);
  return new Response("HDPSB API OK", { headers: corsHeaders });
}

async function handleWebhook(env, update) {
  try {
    const msg = update.message || update.channel_post;
    if (!msg || !msg.text) return new Response("OK");

    const text = msg.text.trim();
    const chatId = msg.chat.id.toString();
    const name = msg.from?.first_name || msg.chat?.title || "Unknown";
    const msgId = msg.message_id?.toString() || "";
    const lower = text.toLowerCase();

    if (lower.startsWith("#selesai") || lower.startsWith("#done")) {
      const parts = text.split(/\s+/);
      const tiketId = parts[1];

      if (!tiketId) {
        await sendMessage(env, chatId, "⚠️ Format: #selesai <ID Tiket>\nContoh: #selesai TKT-20250502-AB12", msgId);
        return new Response("OK");
      }

      const tiket = await env.DB.prepare(
        "SELECT * FROM tickets WHERE laporan_id = ? LIMIT 1"
      ).bind(tiketId).first();

      if (!tiket) {
        await sendMessage(env, chatId, "❌ Tiket tidak ditemukan: " + tiketId, msgId);
        return new Response("OK");
      }

      if (tiket.locked) {
        await sendMessage(env, chatId, "🔒 Tiket ini sudah dikunci sebelumnya.\nID: " + tiketId, msgId);
        return new Response("OK");
      }

      await env.DB.prepare(
        "UPDATE tickets SET status = 'selesai', locked = 1, updated_at = datetime('now') WHERE laporan_id = ?"
      ).bind(tiketId).run();

      await sendMessage(env, chatId,
        "🔒 Tiket berhasil dikunci!\nID Tiket: " + tiketId + "\nStatus: Selesai ✅\nTidak bisa diubah lagi.",
        msgId
      );
      return new Response("OK");
    }

    if (lower.startsWith("#edit")) {
      const parts = text.split(/\s+/);
      const tiketId = parts[1];
      const pesanBaru = parts.slice(2).join(" ");

      if (!tiketId || !pesanBaru) {
        await sendMessage(env, chatId,
          "⚠️ Format: #edit <ID Tiket> <pesan baru>\nContoh: #edit TKT-20250502-AB12 Mohon dicek ulang jaringannya",
          msgId
        );
        return new Response("OK");
      }

      const tiket = await env.DB.prepare(
        "SELECT * FROM tickets WHERE laporan_id = ? LIMIT 1"
      ).bind(tiketId).first();

      if (!tiket) {
        await sendMessage(env, chatId, "❌ Tiket tidak ditemukan: " + tiketId, msgId);
        return new Response("OK");
      }

      if (tiket.locked) {
        await sendMessage(env, chatId,
          "🔒 Tiket ini sudah dikunci dan tidak bisa diedit.\nID: " + tiketId,
          msgId
        );
        return new Response("OK");
      }

      await env.DB.prepare(
        "UPDATE tickets SET message = ?, updated_at = datetime('now') WHERE laporan_id = ?"
      ).bind(pesanBaru, tiketId).run();

      await sendMessage(env, chatId,
        "✏️ Tiket berhasil diperbarui!\nID Tiket: " + tiketId + "\nPesan baru: " + pesanBaru,
        msgId
      );
      return new Response("OK");
    }

    if (!lower.includes("#create") && !lower.includes("#moban")) {
      return new Response("OK");
    }

    const dup = await env.DB.prepare(
      "SELECT laporan_id FROM tickets WHERE chat_id = ? AND message = ? AND created_at > datetime('now', '-10 minutes') LIMIT 1"
    ).bind(chatId, text).first();

    if (dup) {
      await sendMessage(env, chatId,
        "⚠️ Laporan ini sudah tercatat!\nID Tiket: " + dup.laporan_id + "\nTunggu tim kami menghubungi kamu.",
        msgId
      );
      return new Response("OK");
    }

    const now = new Date();
    const pad = n => String(n).padStart(2, "0");
    const rand = Math.random().toString(36).slice(2, 6).toUpperCase();
    const laporanId = "TKT-" + now.getFullYear() + pad(now.getMonth() + 1) + pad(now.getDate()) + "-" + rand;

    await env.DB.prepare(
      "INSERT INTO tickets (created_at, updated_at, chat_id, name, message, message_id, status, laporan_id, locked) VALUES (datetime('now'), datetime('now'), ?, ?, ?, ?, ?, ?, 0)"
    ).bind(chatId, name, text, msgId, "open", laporanId).run();

    await sendMessage(env, chatId,
      "✅ Laporan diterima!\nID Tiket: " + laporanId + "\nTim kami segera menghubungi kamu.",
      msgId
    );

  } catch (err) {
    console.error("Webhook error:", err.message);
  }
  return new Response("OK");
}

async function sendMessage(env, chatId, text, replyToMsgId = null) {
  const body = { chat_id: chatId, text };
  if (replyToMsgId) body.reply_to_message_id = parseInt(replyToMsgId);
  return fetch("https://api.telegram.org/bot" + env.TELEGRAM_TOKEN + "/sendMessage", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body)
  });
}

async function handleGetData(env, corsHeaders) {
  const { results } = await env.DB.prepare(
    "SELECT rowid as _rowIndex, strftime('%Y-%m-%dT%H:%M:%S', created_at) as timestamp, chat_id as chatId, name, message as msg, balasan, pic, status, message_id as messageId, laporan_id as laporanId, CASE WHEN status LIKE '%SELESAI%' THEN 'done' ELSE 'new' END as statusKey FROM tickets ORDER BY rowid ASC"
  ).all();
  const last = await env.DB.prepare("SELECT COALESCE(MAX(rowid), 1) as last FROM tickets").first();
  return Response.json({ rows: results || [], lastIndex: last ? last.last : 1 }, { headers: corsHeaders });
}

async function handleGetNew(env, url, corsHeaders) {
  const lastIndex = parseInt(url.searchParams.get("lastIndex") || "1");
  const { results } = await env.DB.prepare(
    "SELECT rowid as _rowIndex, strftime('%Y-%m-%dT%H:%M:%S', created_at) as timestamp, chat_id as chatId, name, message as msg, balasan, pic, status, message_id as messageId, laporan_id as laporanId, CASE WHEN status LIKE '%SELESAI%' THEN 'done' ELSE 'new' END as statusKey FROM tickets WHERE rowid > ? ORDER BY rowid ASC"
  ).bind(lastIndex).all();
  const last = await env.DB.prepare("SELECT COALESCE(MAX(rowid), ?) as last FROM tickets").bind(lastIndex).first();
  return Response.json({ rows: results || [], lastIndex: last ? last.last : lastIndex, hasNew: (results || []).length > 0 }, { headers: corsHeaders });
}

async function handleKirim(env, body, corsHeaders) {
  const { rowIndex, chatId, balasan, pic, msgId } = body;
  if (!balasan?.trim() || !chatId || !pic) {
    return Response.json({ ok: false, msg: "Data tidak lengkap" }, { headers: corsHeaders });
  }
  const now = new Date();
  const jam = String(now.getUTCHours() + 8).padStart(2, "0");
  const menit = String(now.getUTCMinutes()).padStart(2, "0");
  const status = "SELESAI " + pic + " " + jam + ":" + menit;
  await env.DB.prepare(
    "UPDATE tickets SET balasan = ?, pic = ?, status = ?, updated_at = datetime('now') WHERE rowid = ?"
  ).bind(balasan.trim(), pic, status, rowIndex).run();
  const payload = { chat_id: chatId, text: balasan.trim() + "\n\nSalam,\n" + pic };
  if (msgId) payload.reply_to_message_id = parseInt(msgId);
  const res = await fetch("https://api.telegram.org/bot" + env.TELEGRAM_TOKEN + "/sendMessage", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
  });
  const tgRes = await res.json();
  return Response.json({ ok: tgRes.ok, msg: tgRes.ok ? "Terkirim" : tgRes.description }, { headers: corsHeaders });
}

async function handleKlaim(url, env, corsHeaders) {
  const rowIndex = url.searchParams.get("rowIndex");
  const pic = url.searchParams.get("pic");
  await env.DB.prepare(
    "CREATE TABLE IF NOT EXISTS klaim_cache (row_index TEXT PRIMARY KEY, pic TEXT, claimed_at TEXT)"
  ).run();
  if (pic === "clear") {
    await env.DB.prepare("DELETE FROM klaim_cache WHERE row_index = ?").bind(rowIndex).run();
    return Response.json({ ok: true, klaimedBy: null }, { headers: corsHeaders });
  }
  if (pic === "__check__") {
    const row = await env.DB.prepare("SELECT pic FROM klaim_cache WHERE row_index = ?").bind(rowIndex).first();
    return Response.json({ ok: true, klaimedBy: row ? row.pic : null }, { headers: corsHeaders });
  }
  const existing = await env.DB.prepare("SELECT pic, claimed_at FROM klaim_cache WHERE row_index = ?").bind(rowIndex).first();
  if (existing) {
    const ageMinutes = (Date.now() - new Date(existing.claimed_at).getTime()) / 60000;
    if (ageMinutes < 30 && existing.pic !== pic) {
      return Response.json({ ok: false, klaimedBy: existing.pic }, { headers: corsHeaders });
    }
  }
  await env.DB.prepare(
    "INSERT INTO klaim_cache (row_index, pic, claimed_at) VALUES (?, ?, datetime('now')) ON CONFLICT(row_index) DO UPDATE SET pic = excluded.pic, claimed_at = excluded.claimed_at"
  ).bind(rowIndex, pic).run();
  return Response.json({ ok: true, klaimedBy: pic }, { headers: corsHeaders });
}

async function handleKlaimStatus(url, env, corsHeaders) {
  const rowIndex = url.searchParams.get("rowIndex");
  if (!rowIndex) return Response.json({ pic: null }, { headers: corsHeaders });
  const row = await env.DB.prepare("SELECT pic, claimed_at FROM klaim_cache WHERE row_index = ?").bind(rowIndex).first();
  if (!row) return Response.json({ pic: null }, { headers: corsHeaders });
  const ageMinutes = (Date.now() - new Date(row.claimed_at).getTime()) / 60000;
  if (ageMinutes >= 30) {
    await env.DB.prepare("DELETE FROM klaim_cache WHERE row_index = ?").bind(rowIndex).run();
    return Response.json({ pic: null }, { headers: corsHeaders });
  }
  return Response.json({ pic: row.pic }, { headers: corsHeaders });
}

async function handleClearDb(url, env, corsHeaders) {
  const adminKey = url.searchParams.get("adminKey");
  if (!adminKey || adminKey !== "vanzndt28") {
    return Response.json({ ok: false, msg: "Tidak diizinkan" }, { headers: corsHeaders });
  }
  await env.DB.prepare("DELETE FROM tickets").run();
  await env.DB.prepare("DELETE FROM klaim_cache").run();
  return Response.json({ ok: true, msg: "Database berhasil dikosongkan" }, { headers: corsHeaders });
}
