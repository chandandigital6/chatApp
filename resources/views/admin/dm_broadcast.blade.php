{{-- resources/views/admin/dm_broadcast.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center bg-white shadow-md px-6 py-4 rounded-lg">
      <h2 class="font-bold text-2xl text-gray-800">Broadcast Message</h2>
      <a href="{{ route('dm.inbox') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg">← Back</a>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="max-w-3xl mx-auto mt-6 p-3 rounded bg-green-50 text-green-700 border border-green-200">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="max-w-3xl mx-auto mt-6 p-3 rounded bg-red-50 text-red-700 border border-red-200">
      {{ session('error') }}
    </div>
  @endif

  <div class="py-8">
    <form class="max-w-3xl mx-auto bg-white shadow-xl rounded-2xl p-6 border"
          action="{{ route('dm.broadcast.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
      <textarea name="body" rows="4"
                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-indigo-500"
                placeholder="Type your announcement…">{{ old('body') }}</textarea>
      @error('body') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

      <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Attachments (optional)</label>
        <input type="file" name="attachments[]" multiple
               accept="image/jpeg,image/png,image/webp,image/gif,application/pdf,video/mp4,video/webm,video/quicktime"
               class="w-full border rounded-lg p-2">
        @error('attachments.*') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mt-6 flex items-center justify-end">
        <button type="submit"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
          Send to All Users
        </button>
      </div>
    </form>
  </div>
</x-app-layout>
