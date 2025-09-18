@php /* Font Awesome already loaded in layout */ @endphp

<div class="flex items-center gap-4">
  <button id="dmBell"
          class="relative inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100"
          aria-label="Notifications">
    <i class="fa-regular fa-bell text-2xl leading-none"></i>
    <span id="inboxBadge"
          class="hidden absolute -top-1 -right-1 inline-flex items-center justify-center
                 rounded-full bg-red-600 text-white text-[10px] w-5 h-5">0</span>
  </button>
</div>


 
{{-- notification sound --}}
<audio id="dm-ding" preload="auto">
  <source src="{{ asset('asset/msg.mp3') }}" type="audio/mpeg">
</audio>

@auth
  <script> window.meId = {{ auth()->id() }}; </script>
@endauth
