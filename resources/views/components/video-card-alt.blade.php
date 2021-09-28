<div class="relative rounded shadow bg-white overflow-hidden">
    <button class="hover:brightness-[0.8] transition-all duration-150" type="submit" form="video{{ $video->id }}">
        <video class="{{ $video->is_nsfw ? 'blur-[16px]' : null }}" src="{{ asset('storage/videos/'.$video->file) }}" preload="metadata"></video>
    </button>

    <div class="pr-[16px] pb-[16px] pl-[16px] text-left">
        <b>{{ $video->name }}</b>
    </div>

    @if($video->is_nsfw)
        <span class="absolute top-[16px] left-[16px] px-[8px] rounded-full bg-red-500 text-white">NSFW</span>
    @endif

    <form id="video{{ $video->id }}" method="GET" action="{{ redirect('/video')->getTargetUrl() }}">
        @csrf

        <input type="hidden" name="videoId" value="{{ $video->id }}">
    </form>
</div>
