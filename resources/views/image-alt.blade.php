@extends('layouts.main')

@section('content')

    <form id="download-form" method="POST">
        @csrf
    </form>

    <div class="flex flex-row h-full">
        <img class="w-[80%] rounded shadow bg-white object-contain" src="{{ asset('storage/images/'.$image->file) }}" alt="{{ $image->name }}">

        <div class="flex flex-col flex-1 ml-[16px] p-[16px] rounded shadow bg-white">
            <p><b>Name:</b> {{ $image->name }}</p>

            <p class="mt-[8px]"><b>Description:</b> {{ $image->description }}</p>

            <p class="mt-[8px]"><b>Author:</b> {{ $image->author ?? 'No author' }}</p>

            <p class="mt-[8px]"><b>Agency:</b> {{ $image->agency ?? 'No agency' }}</p>

            <p class="mt-[8px]"><b>Printscreen:</b> {{ $image->print_screen ?? 'No' }}</p>

            <p class="mt-[8px]"><b>NSFW:</b> {{ $image->is_nsfw ? 'Yes' : 'No' }}</p>

            <p class="mt-[8px]">
                <b>Uploaded by:</b> {{ $image->uploadedBy->userDetails->first_name.' '.$image->uploadedBy->userDetails->last_name }}
            </p>

            <p class="mt-[8px]"><b>Upload date:</b> {{ $image->created_at->format('d.m.Y.') }}</p>

            <p class="mt-[8px]">
                <b>Last edited by:</b> {{ $image->lastEditedBy ? $image->lastEditedBy->userDetails->first_name.' '.$image->lastEditedBy->userDetails->last_name : 'Not edited' }}
            </p>

            {{--<p class="mt-[8px]"><b>Expiry date:</b> {{ $image->expiry_date ? $image->expiry_date->format('d.m.Y.') : 'Not specified' }}</p>--}}

            <p class="mt-[8px]"><b>Resolution:</b> {{ $imageInfo['resolution'] }}</p>

            <p class="mt-[8px]"><b>Size:</b> {{ number_format($imageInfo['size'], 2) }} MB</p>

            <div class="flex flex-row mt-[16px]">
                <button class="flex flex-row flex-1 px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="submit" name="imageId" value="{{ $image->id }}" form="download-form">
                    Download
                </button>
            </div>
        </div>
    </div>

@endsection
