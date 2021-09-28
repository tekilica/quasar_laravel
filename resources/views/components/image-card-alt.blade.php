<div class="flex flex-col relative rounded shadow overflow-hidden">
    <button class="h-full hover:brightness-[0.8] transition-all duration-150" type="submit" form="image{{ $image->id }}">
        <img class="h-full object-cover {{ $image->is_nsfw ? 'blur-[16px]' : null }}" src="{{ asset('storage/images/'.$image->file) }}" alt="{{ $image->name }}">
    </button>

    @if($image->is_nsfw)
        <span class="absolute top-[16px] left-[16px] px-[8px] rounded-full bg-red-500 text-white">NSFW</span>
    @endif

    <form id="image{{ $image->id }}" method="GET" action="{{ redirect('/image')->getTargetUrl() }}">
        @csrf

        <input type="hidden" name="imageId" value="{{ $image->id }}">
    </form>
</div>
