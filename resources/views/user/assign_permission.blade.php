<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white bg-gradient-to-r from-red-600 to-blue-800 px-4 py-2 rounded-md shadow-md inline-block">
            Assign Permissions to {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-md p-8 border border-gray-100">
            
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 mb-6 rounded-lg shadow">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('user.assign-permission', $user->id) }}">
                @csrf

                <h3 class="text-xl font-semibold text-gray-700 mb-4">Select Permissions:</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-8">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-lg shadow-sm hover:bg-blue-50 transition">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                   {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700">{{ ucfirst($permission->name) }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-semibold px-6 py-2 rounded-lg shadow transition duration-200">
                        💾 Save Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
