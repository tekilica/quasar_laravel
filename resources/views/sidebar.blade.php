<button class="sidebar-button flex flex-row mt-[28px] w-[32px] h-[32px] rounded text-[#b8c7ce] hover:bg-[#2c3b41] hover:text-white transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/')->getTargetUrl() }}'">
    <span class="absolute w-[32px] fas fa-home"></span>
    <span class="ml-[64px]">Home</span>
</button>

@if(in_array(auth()->user()->userRole->role->name, ['admin', 'hr']))
    <button class="sidebar-button flex flex-row mt-[16px] w-[32px] h-[32px] rounded text-[#b8c7ce] hover:bg-[#2c3b41] hover:text-white transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/users')->getTargetUrl() }}'">
        <span class="absolute w-[32px] fas fa-users"></span>
        <span class="ml-[64px]">Users</span>
    </button>
@endif

@if(in_array(auth()->user()->userRole->role->name, ['admin', 'journalist']))
    <button class="sidebar-button flex flex-row mt-[16px] w-[32px] h-[32px] rounded text-[#b8c7ce] hover:bg-[#2c3b41] hover:text-white transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/news')->getTargetUrl() }}'">
        <span class="absolute w-[32px] fas fa-newspaper"></span>
        <span class="ml-[64px]">News</span>
    </button>
@endif

@if(in_array(auth()->user()->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']))
    <button class="sidebar-button flex flex-row mt-[16px] w-[32px] h-[32px] rounded text-[#b8c7ce] hover:bg-[#2c3b41] hover:text-white transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/images')->getTargetUrl() }}'">
        <span class="absolute w-[32px] fas fa-images"></span>
        <span class="ml-[64px]">Images</span>
    </button>
@endif

@if(in_array(auth()->user()->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']))
    <button class="sidebar-button flex flex-row mt-[16px] w-[32px] h-[32px] rounded text-[#b8c7ce] hover:bg-[#2c3b41] hover:text-white transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/videos')->getTargetUrl() }}'">
        <span class="absolute w-[32px] fas fa-video"></span>
        <span class="ml-[64px]">Videos</span>
    </button>
@endif

@if(in_array(auth()->user()->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']))
    <button class="sidebar-button flex flex-row mt-[16px] w-[32px] h-[32px] rounded text-[#b8c7ce] hover:bg-[#2c3b41] hover:text-white transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/soundtracks')->getTargetUrl() }}'">
        <span class="absolute w-[32px] fas fa-music"></span>
        <span class="ml-[64px]">Soundtracks</span>
    </button>
@endif
