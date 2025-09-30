{{-- resources/views/dashboard/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-white shadow-md px-6 py-4 rounded-lg">
            <h2 class="font-bold text-2xl text-gray-800">Dashboard</h2>
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#c21108] to-[#000308] text-white font-semibold shadow hover:from-[#000308] hover:to-[#c21108]">
                Refresh
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Top: Chat Shortcuts --}}
            <h3 class="text-xl font-semibold text-gray-800 mb-4">💬 Support Chat</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Support Chat (admin) --}}
                <a href="{{ route('dm.admin') }}"
                   class="group p-5 rounded-2xl border bg-white hover:shadow-lg transition flex items-start gap-3">
                    <div class="text-2xl">🛎️</div>
                    <div>
                        <div class="font-semibold text-gray-900">Support Chat</div>
                        <div class="text-sm text-gray-500">Talk with Super Admin</div>
                        <div class="mt-2 inline-flex items-center text-indigo-600 text-sm group-hover:gap-1 transition">
                            Open → 
                        </div>
                    </div>
                </a>

                {{-- People (if can) --}}
                @can('chat-anyone')
                    <a href="{{ route('dm.people') }}"
                       class="group p-5 rounded-2xl border bg-white hover:shadow-lg transition flex items-start gap-3">
                        <div class="text-2xl">👥</div>
                        <div>
                            <div class="font-semibold text-gray-900">People</div>
                            <div class="text-sm text-gray-500">Start a new chat</div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs bg-gray-100 rounded-full">
                                    {{ $peopleCount ?? '-' }} users
                                </span>
                            </div>
                        </div>
                    </a>
                @endcan

                {{-- Inbox --}}
                <a href="{{ route('dm.inbox') }}"
                   class="group p-5 rounded-2xl border bg-white hover:shadow-lg transition flex items-start gap-3">
                    <div class="text-2xl">✉️</div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold text-gray-900">Inbox</div>
                            @if(($unreadCount ?? 0) > 0)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs bg-red-600 text-white rounded-full">
                                    {{ $unreadCount }} new
                                </span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">Your recent messages</div>
                        <div class="mt-2 inline-flex items-center text-indigo-600 text-sm group-hover:gap-1 transition">
                            View →
                        </div>
                    </div>
                </a>

                {{-- Broadcast (if can) --}}
                @can('chat-broadcast')
                    <a href="{{ route('dm.broadcast.create') }}"
                       class="group p-5 rounded-2xl border bg-white hover:shadow-lg transition flex items-start gap-3">
                        <div class="text-2xl">📣</div>
                        <div>
                            <div class="font-semibold text-gray-900">Broadcast</div>
                            <div class="text-sm text-gray-500">Send update to everyone</div>
                            @if(!empty($lastBroadcastAt))
                                <div class="mt-2 text-[12px] text-gray-500">Last: {{ $lastBroadcastAt }}</div>
                            @endif
                            <div class="mt-2 inline-flex items-center text-indigo-600 text-sm group-hover:gap-1 transition">
                                Create →
                            </div>
                        </div>
                    </a>
                @endcan
            </div>

            {{-- Quick broadcast (optional inline composer) --}}
            @can('chat-broadcast')
                <div class="mt-8 bg-white border rounded-2xl shadow-sm">
                    <div class="px-5 py-4 border-b flex items-center justify-between">
                        <div class="font-semibold text-gray-900">Quick Broadcast</div>
                        <a href="{{ route('dm.broadcast.create') }}" class="text-sm text-indigo-600 hover:underline">
                            Open full composer
                        </a>
                    </div>
                    <div class="px-5 py-4">
                        @if (session('success'))
                            <div class="mb-3 px-3 py-2 bg-green-50 text-green-700 rounded-lg border border-green-200">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-3 px-3 py-2 bg-red-50 text-red-700 rounded-lg border border-red-200">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="mb-3 px-3 py-2 bg-red-50 text-red-700 rounded-lg border border-red-200">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('dm.broadcast.store') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <textarea name="body" rows="3" placeholder="Write a short update to all users…"
                                      class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>

                            <div class="flex items-center gap-3">
                                <label class="cursor-pointer inline-flex items-center gap-2 px-3 py-2 border rounded-lg bg-gray-50 hover:bg-gray-100">
                                    📎 Attach files
                                    <input type="file" name="attachments[]" multiple class="hidden"
                                           accept="image/jpeg,image/png,image/webp,image/gif,application/pdf,video/mp4,video/webm,video/quicktime">
                                </label>
                                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                                    Send Broadcast
                                </button>
                            </div>
                            <div class="text-xs text-gray-500">Max 25MB per file (jpg, png, webp, gif, pdf, mp4, webm, mov)</div>
                        </form>
                    </div>
                </div>
            @endcan

        </div>
    </div>
</x-app-layout>

