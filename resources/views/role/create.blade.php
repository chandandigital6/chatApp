<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($role) ? 'Edit Role' : 'Create Role' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">
                    {{ isset($role) ? 'Edit Role' : 'Create New Role' }}
                </h2>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($role) ? route('role.update', $role->id) : route('role.store') }}"
                    method="POST" class="space-y-4">
                    @csrf
                
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Role Name</label>
                        <input type="text" name="name"
                            value="{{ old('name', isset($role) ? $role->name : '') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200"
                            placeholder="Enter role name" required>
                    </div>
                
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Permissions</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($permissions as $permission)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                        class="rounded border-gray-300 focus:ring-blue-500"
                                        {{ in_array($permission->id, $selectedPermissions ?? []) ? 'checked' : '' }}>
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                
                    <div>
                        <button type="submit"
                            class="px-6 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 transition">
                            {{ isset($role) ? 'Update' : 'Submit' }}
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>
