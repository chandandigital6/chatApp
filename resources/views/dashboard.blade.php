<x-app-layout>
    <x-slot name="header">
        {{-- <h2 class="font-bold text-2xl text-white bg-gradient-to-r from-[#c21108] to-[#000308] px-4 py-2 rounded-md shadow-md inline-block">
            {{ __('Dashboard') }}
        </h2> --}}
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

    <div class="mt-10  md:px-32">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center my-12">📊 Business-wise Analytics</h3>


    </div>

</x-app-layout>
