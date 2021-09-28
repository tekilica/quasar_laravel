@extends('layouts.main')

@section('content')

    <div class="flex flex-row h-full gap-[16px]">
        <div class="flex flex-row flex-1 h-full rounded shadow bg-white overflow-auto">
            <a class="twitter-timeline" href="https://twitter.com/TanjugNews?ref_src=twsrc%5Etfw">Tweets by TanjugNews</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>

        <div class="flex flex-row flex-1 h-full rounded shadow bg-white overflow-auto">
            <a class="twitter-timeline" href="https://twitter.com/BetaNewsAgency?ref_src=twsrc%5Etfw">Tweets by BetaNewsAgency</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>

        <div class="flex flex-row flex-1 h-full rounded shadow bg-white overflow-auto">
            <a class="twitter-timeline" href="https://twitter.com/FoNetNews?ref_src=twsrc%5Etfw">Tweets by FoNetNews</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
    </div>

@endsection
