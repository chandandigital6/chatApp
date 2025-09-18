{{-- resources/views/dm/inbox.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center bg-white shadow-md px-6 py-4 rounded-lg">
      <h2 class="font-bold text-2xl text-gray-800">Messages</h2>
    </div>
  </x-slot>

  <div class="py-10">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-xl rounded-2xl p-6 border">
        <div class="divide-y">
          @forelse($threads as $t)
            @php $peer = $peers[$t->peer_id] ?? null; @endphp
            @if($peer)
              <a href="{{ route('dm.show', $peer->id) }}"
                 class="flex items-center justify-between py-3 hover:bg-gray-50 px-2 rounded-lg">
                <div>
                  <div class="font-semibold text-gray-900">{{ $peer->name }}</div>
                  <div class="text-xs text-gray-500">{{ $peer->email }}</div>
                </div>
                <span class="text-xs text-gray-400">Open</span>
              </a>
            @endif
          @empty
            <div class="text-gray-500 py-8 text-center">No conversations yet.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
