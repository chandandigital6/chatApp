// resources/js/dm-realtime.js
function showToast(text) {
  const t = document.createElement('div');
  t.className = 'fixed right-4 bottom-4 bg-black text-white text-sm px-3 py-2 rounded-lg shadow z-50';
  t.textContent = text;
  document.body.appendChild(t);
  setTimeout(()=> t.remove(), 4000);
}

function bumpBadge() {
  const badge = document.getElementById('inboxBadge');
  const val = +(badge.textContent || 0) + 1;
  badge.textContent = val;
  badge.classList.remove('hidden');
}

window.Echo.private(`user.${window.meId}`)
  .listen('.DirectMessageSent', (e) => {
    // e => { id, body, sender:{id,name}, receiver_id, created_at, attachments: [...] }
    const activePeerId = window.activePeerId || null;

    if (activePeerId && +activePeerId === +e.sender.id) {
      // agar same thread open hai to UI me append kar do (beep optional)
      appendIncomingMessageToUI?.(e);
      document.getElementById('dm-ding')?.play().catch(()=>{});
      return;
    }

    // thread open nahi: badge + toast + sound
    bumpBadge();
    showToast(`${e.sender?.name ?? 'New message'}: ${e.body?.slice(0,70) || 'Attachment'}`);
    document.getElementById('dm-ding')?.play().catch(()=>{});
  });
