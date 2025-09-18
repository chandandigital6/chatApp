<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">All Career Applications</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif

         @forelse($applications as $application)
    <div class="bg-white rounded-lg shadow p-6 mb-5 transition hover:shadow-lg">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-semibold text-blue-600">{{ $application->position_name ?? $application->career?->title }}</h3>
                <p class="text-sm text-gray-500">{{ ucfirst($application->status) }}</p>
            </div>
            <div>
                <form action="{{ route('applications.delete', $application) }}" method="get" class="inline-block" onsubmit="return confirm('Delete this application?')">
                    <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
            <div><strong>Name:</strong> {{ $application->name }}</div>
            <div><strong>Email:</strong> {{ $application->email }}</div>
            <div><strong>Phone:</strong> {{ $application->phone }}</div>
            <div><strong>Applied On:</strong> {{ $application->created_at->format('d M Y') }}</div>
            @if($application->message)
            <div class="col-span-2"><strong>Message:</strong> {{ $application->message }}</div>
            @endif
            @if($application->resume)
            <div class="col-span-2">
                <strong>Resume:</strong>
                <a href="{{ asset('storage/' . $application->resume) }}" target="_blank" class="text-blue-600 underline ml-1">
                    View / Download Resume
                </a>
            </div>
            @endif
        </div>
    </div>
@empty
    <div class="text-center text-gray-500 mt-10">
        No applications submitted yet.
    </div>
@endforelse

        </div>
    </div>
</x-app-layout>
