<x-app-layout>


    <x-slot name="header">
        <div class="flex justify-between items-center bg-white shadow-md px-6 py-4 rounded-lg">
            <h2 class="font-bold text-2xl text-gray-800">
                Direct Chat — {{ $peer->name }}
            </h2>
            <a href="{{ route('user.index') }}"
                class="px-5 py-2 bg-gradient-to-r from-[#c21108] to-[#000308] text-white font-semibold rounded-lg shadow-md hover:from-[#000308] hover:to-[#c21108] transition">
                ← Back to Users
            </a>
        </div>
    </x-slot>

  

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">

                {{-- Header strip inside card --}}
                <div class="px-6 pt-6 pb-4 border-b">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg font-semibold text-gray-900">{{ $peer->name }}</div>
                            <div class="text-sm text-gray-500">{{ $peer->email }}</div>
                        </div>
                        <div class="text-xs text-gray-400">Secure • Realtime</div>
                    </div>
                </div>

                {{-- Messages area (scrollable) --}}
                <div id="messages"
                    class="px-4 sm:px-6 py-4 h-[62vh] overflow-y-auto space-y-3 bg-gradient-to-b from-gray-50 to-white">
                    @foreach ($messages as $m)
                        @php $isMe = $m->sender_id === auth()->id(); @endphp
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-[78%] sm:max-w-[70%] rounded-2xl px-4 py-3 shadow
                                        {{ $isMe ? 'bg-indigo-600 text-white' : 'bg-white text-gray-900 border border-gray-200' }}">
                                <div class="text-[11px] opacity-80 mb-1">
                                    {{ $isMe ? 'You' : $m->sender->name }} • {{ $m->created_at->format('d M, H:i') }}
                                </div>

                                @if ($m->body)
                                    <div class="whitespace-pre-wrap break-words mb-1">{{ $m->body }}</div>
                                @endif

                                @if ($m->attachments->count())
                                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                        @foreach ($m->attachments as $att)
                                            @php
                                                $url = Storage::disk('public')->url($att->path);
                                                $mime = $att->mime;
                                            @endphp

                                            @if (Str::startsWith($mime, 'image/'))
                                                <a href="{{ $url }}" target="_blank"
                                                    class="block rounded-lg overflow-hidden border border-gray-200">
                                                    <img src="{{ $url }}" alt="{{ $att->original_name }}"
                                                        class="w-full h-36 object-cover">
                                                </a>
                                            @elseif(Str::startsWith($mime, 'video/'))
                                                <video controls
                                                    class="rounded-lg border border-gray-200 w-full max-h-44">
                                                    <source src="{{ $url }}" type="{{ $mime }}">
                                                </video>
                                            @elseif($mime === 'application/pdf')
                                                <a href="{{ $url }}" target="_blank"
                                                    class="inline-flex items-center gap-2 text-sm underline">
                                                    📄 {{ $att->original_name }}
                                                </a>
                                            @else
                                                <a href="{{ $url }}" target="_blank"
                                                    class="text-sm underline">
                                                    ⬇️ {{ $att->original_name }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Sticky composer (never overflows) --}}
                <div class="px-4 sm:px-6 pb-5 pt-3 border-t bg-white">
                    <form id="chat-form" class="space-y-2" enctype="multipart/form-data">
                        @csrf

                        <div id="previews" class="hidden"></div>

                        <div class="flex items-end gap-3">
                            {{-- Attach button --}}
                            <label
                                class="shrink-0 cursor-pointer px-3 py-3 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100">
                                📎 Attach
                                <input type="file" name="attachments[]" id="attachments" class="hidden" multiple
                                    accept="image/jpeg,image/png,image/webp,image/gif,application/pdf,video/mp4,video/webm,video/quicktime">
                            </label>

                            {{-- Text area grows but stays inside --}}
                            <textarea name="body"
                                class="flex-1 min-h-[48px] max-h-40 border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 resize-y"
                                placeholder="Type a message…" autocomplete="off"></textarea>

                            {{-- Send button stays inside & never wraps out --}}
                            <button type="submit"
                                class="shrink-0 min-w-[92px] h-[48px] px-5 rounded-xl bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Send
                            </button>
                        </div>

                        {{-- filename chips --}}
                        <div id="file-chips" class="mt-2 flex flex-wrap gap-2"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- tiny scrollbar helper (optional) --}}
    <style>
        #messages::-webkit-scrollbar {
            width: 8px;
        }

        #messages::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 9999px;
        }

        #messages:hover::-webkit-scrollbar-thumb {
            background: #c7cad1;
        }
    </style>

    <script type="module">
        const myId = @json(auth()->id());
        const peerId = @json($peer->id);
        const postUrl = @json(route('dm.store', $peer));

        const box = document.getElementById('messages');
        const form = document.getElementById('chat-form');
        const bodyEl = form.querySelector('textarea[name="body"]'); // ✅ FIXED (was input[name=body])
        const fileEl = document.getElementById('attachments');
        const chipsEl = document.getElementById('file-chips');
        const sendBtn = form.querySelector('button[type="submit"]');

        // ---- Realtime listener (guarded) ----
        if (window.Echo) {
            window.Echo
                .private(`user.${myId}`)
                .listen('.DirectMessageSent', (e) => {
                    // only render if it's from the peer we’re chatting with
                    if (e?.sender?.id === peerId) {
                        append(false, e.sender.name, e.body, e.created_at, e.attachments || []);
                    }
                });
        }

        // ---- Attachments: show chips ----
        fileEl.addEventListener('change', () => {
            chipsEl.innerHTML = '';
            const files = Array.from(fileEl.files || []);
            if (!files.length) return;

            files.forEach(f => {
                const chip = document.createElement('span');
                chip.className = 'px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs border';
                chip.textContent = f.name.length > 40 ? f.name.slice(0, 37) + '…' : f.name;
                chipsEl.appendChild(chip);
            });
        });

        // ---- Enter to send (Shift+Enter = newline) ----
        bodyEl.addEventListener('keydown', (ev) => {
            if (ev.key === 'Enter' && !ev.shiftKey) {
                ev.preventDefault();
                form.requestSubmit();
            }
        });

        // ---- Submit (text / files) ----
        form.addEventListener('submit', async (ev) => {
            ev.preventDefault();

            const hasText = !!bodyEl.value.trim();
            const hasFiles = fileEl.files && fileEl.files.length > 0;
            if (!hasText && !hasFiles) return;

            // optimistic UI (my bubble) happens after server ok to keep IDs in sync
            setSending(true);

            try {
                const fd = new FormData(form); // includes _token + attachments[]
                const res = await fetch(postUrl, {
                    method: 'POST',
                    body: fd
                });
                const data = await res.json();

                if (!res.ok || !data?.message) {
                    throw new Error(data?.message || 'Send failed');
                }

                append(true, 'You', data.message.body, data.message.created_at, data.message.attachments || []);
                bodyEl.value = '';
                fileEl.value = '';
                chipsEl.innerHTML = '';
                scrollToBottom();
            } catch (e) {
                console.error(e);
                alert('Message not sent. Please try again.');
            } finally {
                setSending(false);
            }
        });

        // ---- Helpers ----
        function setSending(on) {
            sendBtn.disabled = on;
            sendBtn.classList.toggle('opacity-60', on);
            sendBtn.classList.toggle('cursor-not-allowed', on);
        }

        function append(isMe, name, body, at, attachments = []) {
            const wrap = document.createElement('div');
            wrap.className = 'flex ' + (isMe ? 'justify-end' : 'justify-start');

            // match your server-rendered bubble styles:
            const bubble = document.createElement('div');
            bubble.className =
                (isMe ?
                    'bg-indigo-600 text-white ' :
                    'bg-white text-gray-900 border border-gray-200 ') +
                'max-w-[78%] sm:max-w-[70%] rounded-2xl px-4 py-3 shadow';

            const meta = document.createElement('div');
            meta.className = 'text-[11px] opacity-80 mb-1';
            meta.textContent = `${name} • ${new Date(at).toLocaleString()}`;
            bubble.appendChild(meta);

            if (body && body.trim().length) {
                const text = document.createElement('div');
                text.className = 'whitespace-pre-wrap break-words mb-1';
                text.textContent = body;
                bubble.appendChild(text);
            }

            if (attachments.length) {
                const grid = document.createElement('div');
                grid.className = 'mt-2 grid grid-cols-2 sm:grid-cols-3 gap-2';
                attachments.forEach(att => {
                    const mime = (att.mime || '').toLowerCase();

                    if (mime.startsWith('image/')) {
                        const a = document.createElement('a');
                        a.href = att.url;
                        a.target = '_blank';
                        a.className = 'block rounded-lg overflow-hidden border border-gray-200';
                        const img = document.createElement('img');
                        img.src = att.url;
                        img.alt = att.name || 'image';
                        img.className = 'w-full h-36 object-cover';
                        a.appendChild(img);
                        grid.appendChild(a);
                    } else if (mime.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.controls = true;
                        video.className = 'rounded-lg border border-gray-200 w-full max-h-44';
                        const src = document.createElement('source');
                        src.src = att.url;
                        src.type = mime;
                        video.appendChild(src);
                        grid.appendChild(video);
                    } else if (mime === 'application/pdf') {
                        const a = document.createElement('a');
                        a.href = att.url;
                        a.target = '_blank';
                        a.className = 'inline-flex items-center gap-2 text-sm underline';
                        a.textContent = `📄 ${att.name || 'PDF'}`;
                        grid.appendChild(a);
                    } else {
                        const a = document.createElement('a');
                        a.href = att.url;
                        a.target = '_blank';
                        a.className = 'text-sm underline';
                        a.textContent = `⬇️ ${att.name || 'File'}`;
                        grid.appendChild(a);
                    }
                });
                bubble.appendChild(grid);
            }

            wrap.appendChild(bubble);
            box.appendChild(wrap);
            scrollToBottom();
        }

        function scrollToBottom() {
            box.scrollTop = box.scrollHeight;
        }

        // initial autoscroll
        scrollToBottom();
    </script>

</x-app-layout>
