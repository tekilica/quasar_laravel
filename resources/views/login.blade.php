@extends('layouts.guest')

@section('content')

    <form id="login-form" method="POST">
        @csrf
    </form>

    <div class="flex flex-row fixed h-full w-full justify-center items-center">
        <div class="flex flex-col w-[1080px] h-[720px] rounded shadow bg-center bg-no-repeat justify-center items-center" style="background-image: url({{ asset('img/quasar.jpg') }})">
            <div class="w-[384px] p-[16px] rounded shadow bg-white">
                <div class="flex flex-row mb-[16px] pb-[8px] border-b">
                    <p class="text-xl">Login</p>
                </div>

                <div class="mt-[16px] text-red-500">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <p>- {{ $error }}</p>
                        @endforeach
                    @endif
                </div>

                <label class="block mt-[16px]" for="email">Email</label>
                <input id="email" class="block w-full rounded-full" type="email" name="email" form="login-form">

                <label class="block mt-[16px]" for="password">Password</label>
                <input id="password" class="block w-full rounded-full" type="password" name="password" form="login-form">

                <button class="flex flex-row w-full mt-[16px] px-[16px] py-[8px] rounded-full shadow bg-[#2c3b41] text-white justify-center items-center" type="submit" form="login-form">
                    Log in
                </button>
            </div>
        </div>
    </div>

@endsection
