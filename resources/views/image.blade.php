@extends('layouts.main')

@section('content')

    <form id="download-form" method="POST">
        @csrf
    </form>

    <form id="update-form" method="POST" action="{{ redirect('/update-image')->getTargetUrl() }}">
        @csrf
    </form>

    <div class="flex flex-row h-full">
        <img class="w-[80%] rounded shadow bg-white object-contain" src="{{ asset('storage/images/'.$image->file) }}" alt="{{ $image->name }}">

        <div class="flex flex-col flex-1 ml-[16px] p-[16px] rounded shadow bg-white">
            <label class="block" for="name">Name</label>
            <input id="name" class="block w-full rounded-full" type="text" name="name" value="{{ $image->name }}" form="update-form">

            <label class="mt-[8px] block" for="description">Description</label>
            <textarea id="description" class="block full rounded" rows="5" name="description" form="update-form">{{ $image->description }}</textarea>

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

                <button class="flex flex-row flex-1 ml-[16px] px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="submit" name="imageId" value="{{ $image->id }}" form="update-form">
                    Save changes
                </button>
            </div>
        </div>
    </div>

@endsection
