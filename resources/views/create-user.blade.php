@extends('layouts.main')

@section('content')

    <form id="user-form" method="POST">
        @csrf
    </form>

    <div class="flex flex-col h-full justify-center items-center">
        <div class="p-[16px] rounded shadow bg-white">
            <div class="flex flex-row pb-[8px] border-b">
                <p class="text-xl">Create user</p>
            </div>

            <div class="flex flex-row mt-[16px]">
                <div class="w-[256px]">
                    <label class="block" for="first-name">First name</label>
                    <input id="first-name" class="block w-full rounded-full" type="text" name="firstName" form="user-form">
                </div>

                <div class="w-[256px] ml-[16px]">
                    <label class="block" for="last-name">Last name</label>
                    <input id="last-name" class="block w-full rounded-full" type="text" name="lastName" form="user-form">
                </div>
            </div>

            <div class="flex flex-row mt-[16px]">
                <div class="w-[256px]">
                    <label class="block" for="gender">Gender</label>
                    <select id="gender" class="block w-full rounded-full" name="gender" form="user-form">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="w-[256px] ml-[16px]">
                    <label class="block" for="role">Role</label>
                    <select id="role" class="block w-full rounded-full" name="role" form="user-form">
                        @foreach($roles as $role)
                            <option value="{{ $role['value'] }}">{{ $role['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex flex-row">
                <div class="w-[256px]">
                    <label class="block" for="email">Email</label>
                    <input id="email" class="block w-full rounded-full" type="email" name="email" form="user-form">
                </div>

                <div class="w-[256px] ml-[16px]">
                    <label class="block" for="phone-number">Phone number</label>
                    <input id="phone-number" class="block w-full rounded-full" type="text" name="phoneNumber" form="user-form">
                </div>
            </div>

            <div class="flex flex-row mt-[16px]">
                <button class="flex flex-row flex-1 px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="submit" form="user-form">
                    Create user
                </button>

                <button class="flex flex-row flex-1 ml-[16px] px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="button" onclick="window.location.href = '{{ redirect('/users')->getTargetUrl() }}'">
                    Cancel
                </button>
            </div>
        </div>
    </div>

@endsection
