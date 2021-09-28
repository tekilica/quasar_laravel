@extends('layouts.main')

@section('content')

    <form id="edit-form" method="POST">
        @csrf
    </form>

    <div class="movable flex flex-row z-10 fixed h-[80px] top-[96px] right-[32px] p-[16px] rounded shadow bg-white transition-all duration-500 justify-end items-center" style="left: 96px;">
        <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="submit" form="edit-form">
            <span class="fas fa-save mr-[8px]"></span>
            Save changes
        </button>

        <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="reset" form="edit-form">
            <span class="fas fa-undo mr-[8px]"></span>
            Reset
        </button>

        <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="button" onclick="window.location.href = '{{ redirect('/images')->getTargetUrl() }}'">
            <span class="fas fa-times mr-[8px]"></span>
            Cancel
        </button>
    </div>

    <div class="mt-[96px] rounded shadow bg-white overflow-hidden">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th class="px-[16px] text-blue-500">
                        Image
                    </th>

                    <th class="px-[16px] border-l text-blue-500">
                        Name
                    </th>

                    <th class="px-[16px] border-l text-blue-500">
                        Description
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach($images as $image)
                    <input type="hidden" name="ids[]" value="{{ $image->id }}" form="edit-form">

                    <tr>
                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t">
                            <img class="w-full h-[128px] rounded object-cover" src="{{ asset('storage/images/'.$image->file) }}" alt="{{ $image->name }}">
                        </td>

                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l">
                            <input class="w-full rounded-full" type="text" name="name[]" value="{{ $image->name }}" form="edit-form">
                        </td>

                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l">
                            <textarea class="w-full rounded" rows="3" name="description[]" form="edit-form">{{ $image->description }}</textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
