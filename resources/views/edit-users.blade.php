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

        <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="button" onclick="window.location.href = '{{ redirect('/users')->getTargetUrl() }}'">
            <span class="fas fa-times mr-[8px]"></span>
            Cancel
        </button>
    </div>

    <div class="mt-[96px] rounded shadow bg-white overflow-hidden">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th class="px-[16px] text-blue-500">
                        First name
                    </th>

                    <th class="px-[16px] border-l text-blue-500">
                        Last name
                    </th>

                    <th class="px-[16px] border-l text-blue-500">
                        Gender
                    </th>

                    <th class="px-[16px] border-l text-blue-500">
                        Role
                    </th>

                    <th class="px-[16px] border-l text-blue-500">
                        Email
                    </th>

                    <th class="px-[16px] border-l text-blue-500">
                        Phone number
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                    <input type="hidden" name="ids[]" value="{{ $user->id }}" form="edit-form">

                    <tr>
                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t">
                            <input class="w-full rounded-full" type="text" name="firstName[]" value="{{ $user->userDetails->first_name }}" form="edit-form">
                        </td>

                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l">
                            <input class="w-full rounded-full" type="text" name="lastName[]" value="{{ $user->userDetails->last_name }}" form="edit-form">
                        </td>

                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l">
                            <select class="w-full rounded-full" name="gender[]" form="edit-form">
                                <option value="male" {{ $user->userDetails->gender == 'male' ? 'selected' : null }}>Male</option>
                                <option value="female" {{ $user->userDetails->gender == 'female' ? 'selected' : null }}>Female</option>
                            </select>
                        </td>

                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l">
                            <select class="w-full rounded-full" name="role[]" form="edit-form">
                                @foreach($roles as $role)
                                    <option value="{{ $role['value'] }}" {{ $user->userRole->role->id == $role['value'] ? 'selected' : null }}>{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l">
                            <input class="w-full rounded-full" type="email" name="email[]" value="{{ $user->email }}" form="edit-form">
                        </td>

                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l">
                            <input class="w-full rounded-full" type="text" name="phoneNumber[]" value="{{ $user->userDetails->phone_number }}" form="edit-form">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
