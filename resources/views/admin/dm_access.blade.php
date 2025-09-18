<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white shadow-md px-6 py-4 rounded-lg">
            <h2 class="font-bold text-2xl text-gray-800">
                DM Access — {{ $user->name }}
            </h2>
            <a href="{{ route('user.index') }}"
               class="px-5 py-2 bg-gradient-to-r from-[#c21108] to-[#000308] text-white font-semibold rounded-lg shadow-md hover:from-[#000308] hover:to-[#c21108]">
               ← Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
                @if(session('success'))
                    <div class="mb-4 flex items-center bg-green-100 text-green-700 px-4 py-3 rounded-lg shadow">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.dm.update', $user) }}" class="space-y-6">
                    @csrf

                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="chat_anyone" value="1"
                               class="w-5 h-5 rounded border-gray-300"
                               {{ $hasGlobal ? 'checked' : '' }}>
                        <span class="font-medium text-gray-800">Can chat with anyone</span>
                    </label>

                    <div>
                        <div class="mb-2 font-medium text-gray-800">Allowed peers (when not global):</div>
                        <select name="peers[]" multiple size="12"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @foreach($allUsers as $u)
                                <option value="{{ $u->id }}" {{ in_array($u->id, $allowed) ? 'selected' : '' }}>
                                    {{ $u->name }} — {{ $u->email }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Tip: Ctrl/Cmd दबाकर multiple select करें</p>
                    </div>

                    <button class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
