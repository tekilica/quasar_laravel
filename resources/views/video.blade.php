@extends('layouts.main')

@section('content')

    <form id="download-form" method="POST">
        @csrf
    </form>

    <form id="update-form" method="POST" action="{{ redirect('/update-video')->getTargetUrl() }}">
        @csrf
    </form>

    <div class="flex flex-row h-full">
        <video class="w-[80%] rounded shadow bg-black object-contain" src="{{ asset('storage/videos/'.$video->file) }}" controls></video>

        <div class="flex flex-col flex-1 ml-[16px] p-[16px] rounded shadow bg-white">
            <label class="block" for="name">Name</label>
            <input id="name" class="block w-full rounded-full" type="text" name="name" value="{{ $video->name }}" form="update-form">

            <label class="mt-[8px] block" for="description">Description</label>
            <textarea id="description" class="block full rounded" rows="5" name="description" form="update-form">{{ $video->description }}</textarea>

            <p class="mt-[8px]"><b>Author:</b> {{ $video->author ?? 'No author' }}</p>

            <p class="mt-[8px]"><b>Agency:</b> {{ $video->agency ?? 'No agency' }}</p>

            <p class="mt-[8px]"><b>Platform:</b> {{ $video->platform ?? 'No' }}</p>

            <p class="mt-[8px]"><b>NSFW:</b> {{ $video->is_nsfw ? 'Yes' : 'No' }}</p>

            <p class="mt-[8px]">
                <b>Uploaded by:</b> {{ $video->uploadedBy->userDetails->first_name.' '.$video->uploadedBy->userDetails->last_name }}
            </p>

            <p class="mt-[8px]"><b>Upload date:</b> {{ $video->created_at->format('d.m.Y.') }}</p>

            <p class="mt-[8px]">
                <b>Last edited by:</b> {{ $video->lastEditedBy ? $video->lastEditedBy->userDetails->first_name.' '.$video->lastEditedBy->userDetails->last_name : 'Not edited' }}
            </p>

            <p class="mt-[8px]"><b>Resolution:</b> {{ $videoInfo['resolution'] }}</p>

            <p class="mt-[8px]"><b>Duration:</b> {{ $videoInfo['duration'] }}</p>

            <p class="mt-[8px]"><b>Bitrate:</b> {{ number_format($videoInfo['bitrate'], 2) }} Kbps</p>

            <p class="mt-[8px]"><b>Size:</b> {{ number_format($videoInfo['size'], 2) }} MB</p>

            <div class="flex flex-row mt-[16px]">
                <button class="flex flex-row flex-1 px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="submit" name="videoId" value="{{ $video->id }}" form="download-form">
                    Download
                </button>

                <button class="flex flex-row flex-1 ml-[16px] px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="submit" name="videoId" value="{{ $video->id }}" form="update-form">
                    Save changes
                </button>
            </div>
        </div>
    </div>

@endsection
