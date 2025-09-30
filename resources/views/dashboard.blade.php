<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white shadow-md px-6 py-4 rounded-lg">
            <h2 class="font-bold text-2xl text-gray-800">
                {{ __('Dashboard') }}
            </h2>

            <a href="{{ route('dashboard') }}"
                class="font-bold text-2xl text-white bg-gradient-to-r from-[#c21108] to-[#000308] px-4 py-2 rounded-md shadow-md inline-block hover:from-[#000308] hover:to-[#c21108] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#c21108] transition duration-300 ease-in-out">
                {{ __('Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="mt-10 md:px-32">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center my-12">
            📊 Business-wise Analytics
        </h3>

        {{-- ✅ Support Chat Routes in Dashboard --}}
        <div class="flex flex-wrap gap-4 justify-center">
            <x-nav-link :href="route('dm.admin')" :active="request()->routeIs('dm.admin')">
                <i class="fas fa-tachometer-alt mr-1"></i> Support Chat
            </x-nav-link>

            @can('chat-anyone')
                <x-nav-link :href="route('dm.people')" :active="request()->routeIs('dm.people')">
                    👥 People
                </x-nav-link>
            @endcan

            @can('inbox-access')
                <x-nav-link :href="route('dm.inbox')" :active="request()->routeIs('dm.inbox')">
                    ✉️ Inbox
                </x-nav-link>
            @endcan

            @can('chat-broadcast')
                <a href="{{ route('dm.broadcast.create') }}"
                    class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 {{ request()->routeIs('dm.broadcast.*') ? 'bg-gray-50 font-semibold' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Broadcast Message</span>
                </a>
            @endcan
        </div>
        {{-- ✅ End Routes --}}
    </div>
</x-app-layout>
