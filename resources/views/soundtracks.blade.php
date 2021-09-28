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
            let soundtrackList = document.getElementById("soundtrack-list");

            if(filterPanel.style.height != "0px" && filterPanel.style.height.length > 0) {
                filterPanel.style.height = "0px";
                soundtrackList.style.marginTop = "96px"
            }
            else {
                filterPanel.style.height = filterPanel.scrollHeight + "px";
                soundtrackList.style.marginTop = (96 + filterPanel.scrollHeight).toString() + "px";
            }
        }
    </script>

    <form id="filter-form" method="GET">
        @csrf
    </form>

    <form id="update-form" method="POST">
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
                @if(in_array(auth()->user()->userRole->role->name, ['admin', 'photographer', 'operator']))
                    <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-green-500 text-white hover:bg-green-400 transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/upload-soundtrack')->getTargetUrl() }}'">
                        <span class="fas fa-plus mr-[8px]"></span>
                        Add new
                    </button>
                @endif

                @if(in_array(auth()->user()->userRole->role->name, ['admin', 'photographer', 'operator']))
                    <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full shadow bg-yellow-500 text-white hover:bg-yellow-400 transition-all duration-150 items-center" type="submit" name="route" value="edit-soundtracks" form="update-form">
                        <span class="fas fa-pen mr-[8px]"></span>
                        Edit
                    </button>
                @endif

                @if(in_array(auth()->user()->userRole->role->name, ['admin', 'photographer', 'operator']))
                    <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full shadow bg-red-500 text-white hover:bg-red-400 transition-all duration-150 items-center" type="submit" name="route" value="delete-soundtracks" form="update-form">
                        <span class="fas fa-trash mr-[8px]"></span>
                        Delete
                    </button>
                @endif

                <label class="ml-[32px] pl-[32px] border-l" for="sort">Sort</label>
                <select id="sort" class="ml-[8px] rounded-full" name="sort" form="filter-form" onchange="this.form.submit()">
                    <option value="upload date" {{ $sort == 'upload date' ? 'selected' : null }}>Oldest first</option>
                    <option value="upload date desc" {{ $sort == 'upload date desc' ? 'selected' : null }}>Newest first</option>
                    <option value="duration" {{ $sort == 'duration' ? 'selected' : null }}>Shortest first</option>
                    <option value="duration desc" {{ $sort == 'duration desc' ? 'selected' : null }}>Longest first</option>
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
                        <p class="text-blue-500"><b>Upload date:</b></p>

                        <div class="flex flex-row mt-[8px] items-center">
                            <label for="date-from">From</label>
                            <input id="date-from" class="ml-[8px] rounded-full" type="date" name="dateFrom" value="{{ $filters['dateFrom'] }}" form="filter-form" onchange="this.form.submit()">

                            <label class="ml-[32px]" for="date-to">To</label>
                            <input id="date-to" class="ml-[8px] rounded-full" type="date" name="dateTo" value="{{ $filters['dateTo'] }}" form="filter-form" onchange="this.form.submit()">
                        </div>
                    </div>

                    <div class="flex flex-col ml-[32px] pl-[32px] border-l">
                        <p class="text-blue-500"><b>Other:</b></p>

                        <div class="flex flex-row flex-1 mt-[8px] items-center">
                            <label for="nsfw">No NSFW</label>
                            <input id="nsfw" class="ml-[8px] rounded-full" type="checkbox" name="nsfw" value="true" form="filter-form" onchange="this.form.submit()" {{ $filters['nsfw'] ? 'checked' : null}}>
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

    <div id="soundtrack-list" class="mt-[96px] rounded-t shadow bg-white transition-all duration-500 overflow-hidden">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    @if(in_array(auth()->user()->userRole->role->name, ['admin', 'photographer', 'operator']))
                        <th class="px-[16px] text-blue-500">
                            Select
                        </th>
                    @endif

                    <th class="px-[16px] border-l text-blue-500">
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
                        @if(in_array(auth()->user()->userRole->role->name, ['admin', 'photographer', 'operator']))
                            <td class="px-[16px] py-[8px] {{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t">
                                <input class="block m-auto rounded-full" type="checkbox" name="selectedIds[]" value="{{ $soundtrack->id }}" form="update-form">
                            </td>
                        @endif

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

@endsection
