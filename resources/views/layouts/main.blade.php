<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <script>
            setTimeout(function() {
                let notification = document.getElementById('notification');

                if(notification.innerText.length > 0)
                    showNotification();
            }, 500)

            function showNotification() {
                document.getElementById('notification').style.top = "8px";

                setTimeout(hideNotification, 3000);
            }

            function hideNotification() {
                document.getElementById('notification').style.top = "-80px";
            }

            let isSidebarOpen = false;

            function toggleSidebar() {
                let sidebar = document.getElementById("sidebar");
                let content = document.getElementById("content");
                let buttons = document.getElementsByClassName("sidebar-button");
                let fixedItems = document.getElementsByClassName("movable");

                if(isSidebarOpen) {
                    sidebar.style.width = "64px";
                    content.style.left = "64px"

                    for(let i = 0; i < buttons.length; i++)
                        buttons.item(i).style.width = "32px";

                    for(let i = 0; i < fixedItems.length; i++) {
                        let leftString = fixedItems.item(i).style.left;
                        let leftNumber = parseInt(leftString.substring(0, leftString.length - 2), 10);
                        leftNumber -= 192;
                        fixedItems.item(i).style.left = leftNumber.toString() + "px";
                    }

                    isSidebarOpen = false;
                }
                else {
                    sidebar.style.width = "256px"
                    content.style.left = "256px";

                    for(let i = 0; i < buttons.length; i++)
                        buttons.item(i).style.width = "192px";

                    for(let i = 0; i < fixedItems.length; i++) {
                        let leftString = fixedItems.item(i).style.left;
                        let leftNumber = parseInt(leftString.substring(0, leftString.length - 2), 10);
                        leftNumber += 192;
                        fixedItems.item(i).style.left = leftNumber.toString() + "px";
                    }

                    isSidebarOpen = true;
                }
            }
        </script>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>

    <body class="antialiased">
        <div class="h-screen bg-gray-100">
            <header class="flex flex-row sticky h-[64px] top-[0px] shadow bg-white">
                <div class="flex flex-row flex-1 items-center">
                    <button class="flex flex-row w-[48px] h-[48px] mx-[8px] rounded-full bg-white text-xl hover:bg-blue-500 hover:text-white transition-all duration-150 justify-center items-center" type="button" onclick="toggleSidebar()">
                        ☰
                    </button>

                    <a class="ml-[16px] text-xl" href="#">quasar</a>
                </div>

                <div class="flex flex-row flex-1 items-center justify-end">
                    <a class="mr-[32px]" href="{{ redirect('/log-out')->getTargetUrl() }}">Log out</a>
                </div>
            </header>

            <div id="sidebar" class="flex flex-col fixed w-[64px] h-full pl-[16px] shadow bg-[#222d32] text-xl transition-all duration-500 overflow-hidden">
                @include('sidebar')
            </div>

            <div id="content" class="fixed top-[64px] right-0 bottom-0 left-[64px] p-[32px] transition-all duration-500 overflow-auto">
                @yield('content')
            </div>

            <button id="notification" class="flex flex-row fixed h-[72px] right-0 left-0 mx-auto px-[16px] py-[8px] rounded shadow bg-blue-500 text-white hover:bg-blue-400 transition-all duration-500 justify-center items-center" type="button" onclick="hideNotification()" style="top: -80px;">
                {{ session('notification') ?? null }}
            </button>
        </div>
    </body>
</html>
