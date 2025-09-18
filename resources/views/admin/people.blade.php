<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white shadow-md px-6 py-4 rounded-lg">
            <h2 class="font-bold text-2xl text-gray-800">People</h2>
            <div class="flex gap-2">
                <a href="{{ route('dm.admin') ?? '#' }}"
                   class="px-4 py-2 bg-gradient-to-r from-[#c21108] to-[#000308] text-white rounded-lg">
                    💬 Support Chat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">

                <form method="GET" action="{{ route('dm.people') }}" class="mb-6">
                    <div class="flex gap-3">
                        <input name="q" value="{{ $q }}"
                               class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                               placeholder="Search name / email / phone…">
                        <button class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">
                            Search
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700 uppercase font-semibold text-xs">
                            <tr>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Phone</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                        @forelse($users as $u)
                            @php
                                // UI guard: show button only if Gate allows (safety already enforced in controller too)
                                $canStart = Gate::allows('dm-start', $u->id);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $u->name }}</td>
                                <td class="px-6 py-3">{{ $u->email }}</td>
                                <td class="px-6 py-3">{{ $u->phone_number ?? 'N/A' }}</td>
                                <td class="px-6 py-3">
                                    @if($canStart)
                                        <a href="{{ route('dm.show', $u->id) }}"
                                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md shadow">
                                            Start Chat
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Not allowed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
