@extends('layouts.main')

@section('content')

    <form id="download-form" method="POST">
        @csrf
    </form>

    <form id="update-form" method="POST" action="{{ redirect('/update-soundtrack')->getTargetUrl() }}">
        @csrf
    </form>

    <div class="flex flex-row h-full">
        <div class="flex flex-row w-[80%] rounded shadow bg-white items-center">
            <audio class="w-full object-contain" src="{{ asset('storage/soundtracks/'.$soundtrack->file) }}" controls></audio>
        </div>

        <div class="flex flex-col flex-1 ml-[16px] p-[16px] rounded shadow bg-white">
            <label class="block" for="name">Name</label>
            <input id="name" class="block w-full rounded-full" type="text" name="name" value="{{ $soundtrack->name }}" form="update-form">

            <label class="mt-[8px] block" for="description">Description</label>
            <textarea id="description" class="block full rounded" rows="5" name="description" form="update-form">{{ $soundtrack->description }}</textarea>

            <p class="mt-[8px]"><b>Author:</b> {{ $soundtrack->author ?? 'No author' }}</p>

            <p class="mt-[8px]"><b>NSFW:</b> {{ $soundtrack->is_nsfw ? 'Yes' : 'No' }}</p>

            <p class="mt-[8px]">
                <b>Uploaded by:</b> {{ $soundtrack->uploadedBy->userDetails->first_name.' '.$soundtrack->uploadedBy->userDetails->last_name }}
            </p>

            <p class="mt-[8px]"><b>Upload date:</b> {{ $soundtrack->created_at->format('d.m.Y.') }}</p>

            <p class="mt-[8px]">
                <b>Last edited by:</b> {{ $soundtrack->lastEditedBy ? $soundtrack->lastEditedBy->userDetails->first_name.' '.$soundtrack->lastEditedBy->userDetails->last_name : 'Not edited' }}
            </p>

            <p class="mt-[8px]"><b>Duration:</b> {{ $soundtrackInfo['duration'] }}</p>

            <p class="mt-[8px]"><b>Bitrate:</b> {{ number_format($soundtrackInfo['bitrate'], 2) }} Kbps</p>

            <p class="mt-[8px]"><b>Size:</b> {{ number_format($soundtrackInfo['size'], 2) }} MB</p>

            <div class="flex flex-row mt-[16px]">
                <button class="flex flex-row flex-1 px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="submit" name="soundtrackId" value="{{ $soundtrack->id }}" form="download-form">
                    Download
                </button>

                <button class="flex flex-row flex-1 ml-[16px] px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="submit" name="soundtrackId" value="{{ $soundtrack->id }}" form="update-form">
                    Save changes
                </button>
            </div>
        </div>
    </div>

@endsection
