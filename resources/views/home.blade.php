@extends('layouts.main')

@section('content')

    <div class="flex flex-col gap-[32px] transition-all duration-500">
        @if(in_array(auth()->user()->userRole->role->name, ['admin', 'hr']))
            <div class="p-[16px] rounded shadow bg-white">
                <div class="flex flex-row pb-[8px] border-b justify-between items-end">
                    <p class="text-xl">New users</p>

                    <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="button" onclick="window.location.href = '{{ redirect('/users')->getTargetUrl() }}'">
                        Show all users
                    </button>
                </div>

                <div class="mt-[16px] border">
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

                                <th class="px-[16px] border-l text-blue-500">
                                    Date created
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $user->userDetails->first_name }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $user->userDetails->last_name }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $user->userDetails->gender }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $user->userRole->role->name }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $user->userDetails->phone_number }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $user->created_at->format('d.m.Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if(in_array(auth()->user()->userRole->role->name, ['admin', 'journalist']))
            <div class="p-[16px] rounded shadow bg-white">
                <div class="flex flex-row pb-[8px] border-b justify-between items-end">
                    <p class="text-xl">News peek</p>

                    <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="button" onclick="window.location.href = '{{ redirect('/news')->getTargetUrl() }}'">
                        Show news
                    </button>
                </div>

                <div class="flex flex-row h-[384px] mt-[16px] gap-[16px]">
                    <div class="flex flex-row flex-1 h-full border overflow-auto">
                        <a class="twitter-timeline" href="https://twitter.com/TanjugNews?ref_src=twsrc%5Etfw">Tweets by TanjugNews</a>
                        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                    </div>

                    <div class="flex flex-row flex-1 h-full border overflow-auto">
                        <a class="twitter-timeline" href="https://twitter.com/BetaNewsAgency?ref_src=twsrc%5Etfw">Tweets by BetaNewsAgency</a>
                        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                    </div>

                    <div class="flex flex-row flex-1 h-full border overflow-auto">
                        <a class="twitter-timeline" href="https://twitter.com/FoNetNews?ref_src=twsrc%5Etfw">Tweets by FoNetNews</a>
                        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                    </div>
                </div>
            </div>
        @endif

        @if(in_array(auth()->user()->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']))
            <div class="p-[16px] rounded shadow bg-white">
                <div class="flex flex-row pb-[8px] border-b justify-between items-end">
                    <p class="text-xl">New images</p>

                    <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="button" onclick="window.location.href = '{{ redirect('/images')->getTargetUrl() }}'">
                        Show all images
                    </button>
                </div>

                <div class="grid grid-cols-6 mt-[16px] gap-[16px]">
                    @foreach($images as $image)
                        @include('components.image-card-alt', ['image' => $image])
                    @endforeach
                </div>
            </div>
        @endif

        @if(in_array(auth()->user()->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']))
            <div class="p-[16px] rounded shadow bg-white">
                <div class="flex flex-row pb-[8px] border-b justify-between items-end">
                    <p class="text-xl">New videos</p>

                    <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="button" onclick="window.location.href = '{{ redirect('/videos')->getTargetUrl() }}'">
                        Show all videos
                    </button>
                </div>

                <div class="grid grid-cols-6 mt-[16px] gap-[16px]">
                    @foreach($videos as $video)
                        @include('components.video-card-alt', ['video' => $video])
                    @endforeach
                </div>
            </div>
        @endif

        @if(in_array(auth()->user()->userRole->role->name, ['admin', 'journalist', 'photographer', 'operator']))
            <div class="p-[16px] rounded shadow bg-white">
                <div class="flex flex-row pb-[8px] border-b justify-between items-end">
                    <p class="text-xl">New soundtracks</p>

                    <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-blue-500 text-white justify-center items-center" type="button" onclick="window.location.href = '{{ redirect('/soundtracks')->getTargetUrl() }}'">
                        Show all soundtracks
                    </button>
                </div>

                <div class="mt-[16px] border">
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-[16px] text-blue-500">
                                    Soundtrack
                                </th>

                                <th class="px-[16px] border-l text-blue-500">
                                    Name
                                </th>

                                <th class="px-[16px] border-l text-blue-500">
                                    Author
                                </th>

                                <th class="px-[16px] border-l text-blue-500">
                                    NSFW
                                </th>

                                <th class="px-[16px] border-l text-blue-500">
                                    Uploaded by
                                </th>

                                <th class="px-[16px] border-l text-blue-500">
                                    Date uploaded
                                </th>

                                <th class="px-[16px] border-l text-blue-500">
                                    Control
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($soundtracks as $soundtrack)
                                <form id="view-form{{ $soundtrack->id }}" method="GET" action="{{ redirect('/soundtrack')->getTargetUrl() }}">
                                    @csrf
                                </form>

                                <tr>
                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        <audio class="w-full rounded shadow" src="{{ asset('storage/soundtracks/'.$soundtrack->file) }}" preload="metadata" controls></audio>
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $soundtrack->name }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $soundtrack->author ?? 'No author' }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        @if($soundtrack->is_nsfw)
                                            <b class="text-red-500">Yes</b>
                                        @else
                                            No
                                        @endif
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $soundtrack->uploadedBy->userDetails->first_name.' '.$soundtrack->uploadedBy->userDetails->last_name }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        {{ $soundtrack->created_at->format('d.m.Y') }}
                                    </td>

                                    <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t border-l text-center">
                                        <button class="block mx-auto px-[8px] py-[4px] rounded-full shadow bg-blue-500 text-white" type="submit" name="soundtrackId" value="{{ $soundtrack->id }}" form="view-form{{ $soundtrack->id }}">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

@endsection
