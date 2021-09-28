@extends('layouts.main')

@section('content')

    <script>
        setTimeout(function() {
            if(document.getElementById('isFilterPanelOpen').value) {
                toggleFilterPanelVisibility();
            }
        }, 0);

        function toggleFilterPanelVisibility() {
            let filterPanel = document.getElementById("filter-panel");
            let userList = document.getElementById("user-list");

            if(filterPanel.style.height != "0px" && filterPanel.style.height.length > 0) {
                filterPanel.style.height = "0px";
                userList.style.marginTop = "96px"
            }
            else {
                filterPanel.style.height = filterPanel.scrollHeight + "px";
                userList.style.marginTop = (96 + filterPanel.scrollHeight).toString() + "px";
            }
        }
    </script>

    <form id="filter-form" method="GET">
        @csrf
    </form>

    <form id="update-form" method="POST" action="{{ redirect('/update-users')->getTargetUrl() }}">
        @csrf
    </form>

    <div class="movable flex flex-col z-10 fixed top-[96px] right-[32px] rounded shadow bg-white transition-all duration-500 overflow-hidden" style="left: 96px;">
        <div class="flex flex-row m-[16px] justify-between items-center">
            <div class="flex flex-row">
                <input id="isFilterPanelOpen" type="hidden" value="{{ $isFilterPanelOpen }}">

                <input class="w-[384px] rounded-l-full border-r-0 focus:ring-0" type="text" name="search" value="{{ $search }}" form="filter-form">

                @if($search)
                    <button class="flex flex-row px-[16px] border-t border-b border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 items-center" type="submit" name="action" value="clear search" form="filter-form">
                        <span class="fas fa-times"></span>
                    </button>
                @endif

                <button class="flex flex-row px-[16px] rounded-r-full bg-blue-500 text-white items-center" type="submit" form="filter-form">
                    <span class="fas fa-search"></span>
                </button>

                <div class="flex flex-row ml-[32px] pl-[32px] border-l items-center">
                    {{ $results }}
                </div>
            </div>

            <div class="flex flex-row items-center">
                <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-green-500 text-white hover:bg-green-400 transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/create-user')->getTargetUrl() }}'">
                    <span class="fas fa-plus mr-[8px]"></span>
                    Add new
                </button>

                <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full shadow bg-yellow-500 text-white hover:bg-yellow-400 transition-all duration-150 items-center" type="submit" name="route" value="edit-users" form="update-form">
                    <span class="fas fa-pen mr-[8px]"></span>
                    Edit
                </button>

                <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full shadow bg-red-500 text-white hover:bg-red-400 transition-all duration-150 items-center" type="submit" name="route" value="delete-users" form="update-form">
                    <span class="fas fa-trash mr-[8px]"></span>
                    Delete
                </button>

                <label class="ml-[32px] pl-[32px] border-l" for="sort">Sort</label>
                <select id="sort" class="ml-[8px] rounded-full" name="sort" form="filter-form" onchange="this.form.submit()">
                    <option value="first name" {{ $sort == 'first name' ? 'selected' : null }}>First name A-Z</option>
                    <option value="first name desc" {{ $sort == 'first name desc' ? 'selected' : null }}>First name Z-A</option>
                    <option value="last name" {{ $sort == 'last name' ? 'selected' : null }}>Last name A-Z</option>
                    <option value="last name desc" {{ $sort == 'last name desc' ? 'selected' : null }}>Last name Z-A</option>
                    <option value="date created" {{ $sort == 'date created' ? 'selected' : null }}>Oldest first</option>
                    <option value="date created desc" {{ $sort == 'date created desc' ? 'selected' : null }}>Newest first</option>
                </select>

                <button class="flex flex-row w-[48px] h-[48px] ml-[16px] rounded-full bg-white hover:bg-blue-500 hover:text-white transition-all duration-150 justify-center items-center" type="button" onclick="toggleFilterPanelVisibility()">
                    ☰
                </button>
            </div>
        </div>

        <div id="filter-panel" class="h-0 transition-all duration-500">
            <div class="flex flex-row p-[16px] border-t justify-between items-end">
                <div class="flex flex-row">
                    <div class="flex flex-col">
                        <p class="text-blue-500"><b>Gender:</b></p>

                        <div class="flex flex-row mt-[8px] items-center">
                            <label for="male">Male</label>
                            <input id="male" class="ml-[8px] rounded-full" type="checkbox" name="genders[]" value="male" form="filter-form" onchange="this.form.submit()" {{ in_array('male', $filters['gender']) ? 'checked' : null }}>

                            <label class="ml-[32px]" for="female">Female</label>
                            <input id="female" class="ml-[8px] rounded-full" type="checkbox" name="genders[]" value="female" form="filter-form" onchange="this.form.submit()" {{ in_array('female', $filters['gender']) ? 'checked' : null }}>
                        </div>
                    </div>

                    <div class="flex flex-col ml-[32px] pl-[32px] border-l">
                        <p class="text-blue-500"><b>Role:</b></p>

                        <div class="flex flex-row mt-[8px] items-center">
                            @if(auth()->user()->userRole->role->name == 'admin')
                                <label for="admin">Admin</label>
                                <input id="admin" class="ml-[8px] rounded-full" type="checkbox" name="roles[]" value="admin" form="filter-form" onchange="this.form.submit()" {{ in_array('admin', $filters['role']) ? 'checked' : null }}>
                            @endif

                            <label class="{{ auth()->user()->userRole->role->name == 'admin' ? 'ml-[32px]' : null }}" for="hr">HR</label>
                            <input id="hr" class="ml-[8px] rounded-full" type="checkbox" name="roles[]" value="hr" form="filter-form" onchange="this.form.submit()" {{ in_array('hr', $filters['role']) ? 'checked' : null }}>

                            <label class="ml-[32px]" for="journalist">Journalist</label>
                            <input id="journalist" class="ml-[8px] rounded-full" type="checkbox" name="roles[]" value="journalist" form="filter-form" onchange="this.form.submit()" {{ in_array('journalist', $filters['role']) ? 'checked' : null }}>

                            <label class="ml-[32px]" for="photographer">Photographer</label>
                            <input id="photographer" class="ml-[8px] rounded-full" type="checkbox" name="roles[]" value="photographer" form="filter-form" onchange="this.form.submit()" {{ in_array('photographer', $filters['role']) ? 'checked' : null }}>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col">
                    <button class="flex flex-row px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="submit" form="filter-form" name="action" value="reset filters">
                        Reset filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="user-list" class="mt-[96px] rounded-t shadow bg-white transition-all duration-500 overflow-hidden">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th class="px-[16px] text-blue-500">
                        Select
                    </th>

                    <th class="px-[16px] border-l text-blue-500">
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
                        <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t">
                            <input class="block m-auto rounded-full" type="checkbox" name="selectedIds[]" value="{{ $user->id }}" form="update-form">
                        </td>

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

@endsection
