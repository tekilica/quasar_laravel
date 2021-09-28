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
            let videoGrid = document.getElementById("video-grid");

            if(filterPanel.style.height != "0px" && filterPanel.style.height.length > 0) {
                filterPanel.style.height = "0px";
                videoGrid.style.marginTop = "96px"
            }
            else {
                filterPanel.style.height = filterPanel.scrollHeight + "px";
                videoGrid.style.marginTop = (96 + filterPanel.scrollHeight).toString() + "px";
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
                    <button class="flex flex-row px-[16px] py-[8px] rounded-full shadow bg-green-500 text-white hover:bg-green-400 transition-all duration-150 items-center" type="button" onclick="window.location.href = '{{ redirect('/upload-video')->getTargetUrl() }}'">
                        <span class="fas fa-plus mr-[8px]"></span>
                        Add new
                    </button>
                @endif

                @if(in_array(auth()->user()->userRole->role->name, ['admin', 'photographer', 'operator']))
                    <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full shadow bg-yellow-500 text-white hover:bg-yellow-400 transition-all duration-150 items-center" type="submit" name="route" value="edit-videos" form="update-form">
                        <span class="fas fa-pen mr-[8px]"></span>
                        Edit
                    </button>
                @endif

                @if(in_array(auth()->user()->userRole->role->name, ['admin', 'photographer', 'operator']))
                    <button class="flex flex-row ml-[16px] px-[16px] py-[8px] rounded-full shadow bg-red-500 text-white hover:bg-red-400 transition-all duration-150 items-center" type="submit" name="route" value="delete-videos" form="update-form">
                        <span class="fas fa-trash mr-[8px]"></span>
                        Delete
                    </button>
                @endif

                <label class="ml-[32px] pl-[32px] border-l" for="sort">Sort</label>
                <select id="sort" class="ml-[8px] rounded-full" name="sort" form="filter-form" onchange="this.form.submit()">
                    <option value="upload date" {{ $sort == 'upload date' ? 'selected' : null }}>Oldest first</option>
                    <option value="upload date desc" {{ $sort == 'upload date desc' ? 'selected' : null }}>Newest first</option>
                    <option value="resolution" {{ $sort == 'resolution' ? 'selected' : null }}>Lowest resolution first</option>
                    <option value="resolution desc" {{ $sort == 'resolution desc' ? 'selected' : null }}>Highest resolution first</option>
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
                        <p class="text-blue-500"><b>Platform:</b></p>

                        <div class="mt-[8px] flex flex-row items-center">
                            <label for="platform">Name</label>
                            <select id="platform" class="ml-[8px] rounded-full" name="platform" form="filter-form" onchange="this.form.submit()">
                                <option {{ $filters['platform'] ? null : 'selected' }}></option>
                                <option value="None" {{ $filters['platform'] == 'None' ? 'selected' : null }}>None</option>
                                <option value="Any" {{ $filters['platform'] == 'Any' ? 'selected' : null }}>Any</option>
                                <option value="Instagram" {{ $filters['platform'] == 'Instagram' ? 'selected' : null }}>Instagram</option>
                                <option value="Facebook" {{ $filters['platform'] == 'Facebook' ? 'selected' : null }}>Facebook</option>
                                <option value="YouTube" {{ $filters['platform'] == 'YouTube' ? 'selected' : null }}>YouTube</option>
                                <option value="Twitter" {{ $filters['platform'] == 'Twitter' ? 'selected' : null }}>Twitter</option>
                                <option value="TikTok" {{ $filters['platform'] == 'TikTok' ? 'selected' : null }}>TikTok</option>
                                <option value="Other" {{ $filters['platform'] == 'Other' ? 'selected' : null }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col ml-[32px] pl-[32px] border-l">
                        <p class="text-blue-500"><b>Resolution:</b></p>

                        <div class="flex flex-row mt-[8px] items-center">
                            <label for="resolution-criteria">Criteria</label>
                            <select id="resolution-criteria" class="ml-[8px] rounded-full" name="resolutionCriteria" form="filter-form">
                                <option value="at least" {{ $filters['resolutionCriteria'] == 'at least' ? 'selected' : null }}>At least</option>
                                <option value="exactly" {{ $filters['resolutionCriteria'] == 'exactly' ? 'selected' : null }}>Exactly</option>
                            </select>

                            <label class="ml-[32px]" for="definition">Definition</label>
                            <select id="definition" class="ml-[8px] rounded-full" name="definition" form="filter-form">
                                <option {{ $filters['definition'] ? null : 'selected' }}></option>
                                <option value="4320" {{ $filters['definition'] == '4320' ? 'selected' : null }}>4320p - 8K</option>
                                <option value="2160" {{ $filters['definition'] == '2160' ? 'selected' : null }}>2160p - 4K</option>
                                <option value="1440" {{ $filters['definition'] == '1440' ? 'selected' : null }}>1440p - HD</option>
                                <option value="1080" {{ $filters['definition'] == '1080' ? 'selected' : null }}>1080p - HD</option>
                                <option value="720" {{ $filters['definition'] == '720' ? 'selected' : null }}>720p</option>
                                <option value="480" {{ $filters['definition'] == '480' ? 'selected' : null }}>480p</option>
                                <option value="360" {{ $filters['definition'] == '360' ? 'selected' : null }}>360p</option>
                                <option value="240" {{ $filters['definition'] == '240' ? 'selected' : null }}>240p</option>
                            </select>

                            <button class="flex flex-row ml-[32px] px-[16px] py-[8px] rounded-full bg-blue-500 text-white justify-center items-center" type="button" form="filter-form" onclick="this.form.submit()">
                                Apply
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col ml-[32px] pl-[32px] border-l">
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
                    <button class="flex flex-row px-[16px] py-[8px] rounded-full border shadow border-gray-500 bg-white text-gray-500 hover:bg-gray-500 hover:text-white transition-all duration-150 justify-center items-center" type="submit" name="action" value="reset filters" form="filter-form">
                        Reset filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="video-grid" class="grid grid-cols-6 mt-[96px] gap-[16px] transition-all duration-500">
        @foreach($videos as $video)
            @if(in_array(auth()->user()->userRole->role->name, ['admin', 'photographer', 'operator']))
                @include('components.video-card', [
        'video' => $video,
        'form' => 'update-form',
        ])
            @else
                @include('components.video-card-alt', ['video' => $video])
            @endif
        @endforeach
    </div>

@endsection
