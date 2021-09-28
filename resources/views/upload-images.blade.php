@extends('layouts.main')

@section('content')

    <form id="upload-form" method="POST" enctype="multipart/form-data">
        @csrf
    </form>

    <div class="flex flex-col h-full justify-center items-center">
        <div class="p-[16px] rounded shadow bg-white">
            <div class="pb-[8px] border-b">
                <p class="text-xl">Upload images</p>
            </div>

            <div class="mt-[16px] text-red-500">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <p>- {{ $error }}</p>
                    @endforeach
                @endif
            </div>

            <label class="mt-[16px] block" for="images">Images</label>
            <input id="images" class="block w-[384px]" type="file" name="images[]" form="upload-form" multiple>

            <label class="mt-[16px] block" for="name">Name</label>
            <input id="name" class="block w-[384px] rounded-full" type="text" name="name" form="upload-form">

            <label class="mt-[16px] block" for="description">Description</label>
            <textarea id="description" class="block w-[384px] rounded" rows="5" name="description" form="upload-form"></textarea>

            <label class="mt-[16px] block" for="print-screen">Printscreen</label>
            <select id="print-screen" class="block w-[384px] rounded-full" name="printScreen" form="upload-form">
                <option selected></option>
                <option value="Instagram">Instagram</option>
                <option value="Facebook">Facebook</option>
                <option value="Twitter">Twitter</option>
                <option value="TikTok">TikTok</option>
                <option value="Ostalo">Other</option>
            </select>

            <label class="mt-[16px] block" for="author">Author</label>
            <input id="author" class="block w-[384px] rounded-full" type="text" name="author" form="upload-form">

            <label class="mt-[16px] block" for="agency">Agency</label>
            <input id="agency" class="block w-[384px] rounded-full" type="text" name="agency" form="upload-form">

            <div class="flex flex-row mt-[16px] items-center">
                <label for="is-nsfw">NSFW</label>
                <input id="is-nsfw" class="ml-[8px] rounded-full" type="checkbox" name="isNsfw" value="true" form="upload-form">
            </div>

            <div class="flex flex-row mt-[32px]">
                <button class="flex flex-row flex-1 px-[16px] py-[8px] rounded-full bg-blue-500 shadow text-white justify-center items-center" type="submit" form="upload-form">
                    Upload images
                </button>

                <button class="flex flex-row flex-1 ml-[16px] px-[16px] py-[8px] rounded-full bg-white border border-gray-500 shadow text-gray-500 transition-all duration-150 justify-center items-center hover:bg-gray-500 hover:text-white" type="button" onclick="window.location.href = '{{ redirect('/images')->getTargetUrl() }}'">
                    Cancel
                </button>
            </div>
        </div>
    </div>

@endsection
