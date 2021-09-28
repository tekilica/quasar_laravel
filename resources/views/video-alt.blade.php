@extends('layouts.main')

@section('content')

    <form id="download-form" method="POST">
        @csrf
    </form>

    <div class="flex flex-row h-full">
        <video class="w-[80%] rounded shadow bg-white object-contain" src="{{ asset('storage/videos/'.$video->file) }}" controls></video>

        <div class="flex flex-col flex-1 ml-[16px] p-[16px] rounded shadow bg-white">
            <p><b>Name:</b> {{ $video->name }}</p>

            <p class="mt-[8px]"><b>Description:</b> {{ $video->description }}</p>

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
            </div>
        </div>
    </div>

@endsection
