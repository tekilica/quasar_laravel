<?php

namespace App\Http\Controllers;

use getID3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Video;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Collection;

class VideoController extends Controller
{
    public function showVideosView(Request $request)
    {
        if(!Gate::allows('view-videos'))
            abort(403);

        $isFilterPanelOpen = false;

        if($request->action && $request->action == 'clear search')
            $search = null;
        else
            $search = $request->search ?? null;

        if($request->action && $request->action == 'reset filters')
            $filters = [
                'platform' => null,
                'resolutionCriteria' => 'at least',
                'definition' => null,
                'dateFrom' => null,
                'dateTo' => null,
                'nsfw' => false,
            ];
        else
            $filters = [
                'platform' => $request->platform ?? null,
                'resolutionCriteria' => $request->resolutionCriteria,
                'definition' => $request->definition,
                'dateFrom' => $request->dateFrom ?? null,
                'dateTo' => $request->dateTo ?? null,
                'nsfw' => $request->nsfw ?? false,
            ];

        if($this->areVideosFiltered($filters))
            $isFilterPanelOpen = true;

        if($search || $this->areVideosFiltered($filters))
            $videos = $this->getVideosFiltered($search, $filters);
        else
            $videos = Video::all();

        $sort = $request->sort ?? 'upload date';

        return view('videos', [
            'isFilterPanelOpen' => $isFilterPanelOpen,
            'search' => $search,
            'filters' => $filters,
            'sort' => $sort,
            'videos' => $this->sortVideos($videos, $sort),
            'results' => count($videos).' '.Pluralizer::plural('result', count($videos)),
        ]);
    }

    private function areVideosFiltered($filters): bool
    {
        return $filters['platform'] || $filters['definition'] || $filters['dateFrom'] || $filters['dateTo'] || $filters['nsfw'];
    }

    private function getVideosFiltered($search, $filters): Collection
    {
        $videos = [];

        foreach(Video::all() as $video)
            if($this->videoMatchesSearch($video, $search) && $this->videoMatchesFilters($video, $filters))
                array_push($videos, $video);

        return collect($videos);
    }

    private function videoMatchesSearch($video, $search): bool
    {
        if($search
           && !str_contains(mb_strtolower($video->name, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($video->description, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($video->author, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($video->agency, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($video->uploadedBy->userDetails->first_name, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($video->uploadedBy->userDetails->last_name, 'UTF-8'), mb_strtolower($search, 'UTF-8')))
            return false;

        return true;
    }

    private function videoMatchesFilters($video, $filters): bool
    {
        if($filters['platform'])
        {
            if($filters['platform'] == 'None')
            {
                if($video->platform)
                    return false;
            }
            else if($filters['platform'] == 'Any')
            {
                if(!$video->platform)
                    return false;
            }
            else
            {
                if($video->platform != $filters['platform'])
                    return false;
            }
        }

        $videoPath = storage_path('app/public/videos/'.$video->file);

        $getId3 = new getID3();
        $getId3Info = $getId3->analyze($videoPath);

        if($filters['resolutionCriteria'] == 'at least')
            if($filters['definition'] && $getId3Info['video']['resolution_y'] < $filters['definition'])
                return false;
        else if($filters['resolutionCriteria'] == 'exactly')
            if($filters['definition'] && $getId3Info['video']['resolution_y'] != $filters['width'])
                return false;

        if($filters['dateFrom'] && strtotime($video->created_at) < strtotime($filters['dateFrom']))
            return false;

        if($filters['dateTo'] && strtotime($video->created_at) > strtotime($filters['dateTo']))
            return false;

        if($filters['nsfw'] && $video->is_nsfw)
            return false;

        return true;
    }

    private function sortVideos($videos, $sort): Collection
    {
        if(str_contains($sort, 'upload date'))
            $sortedVideos = $videos->sortBy(function($video)
            {
                return strtotime($video->created_at);
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else if(str_contains($sort, 'resolution'))
            $sortedVideos = $videos->sortBy(function($video)
            {
                $videoPath = storage_path('app/public/videos/'.$video->file);

                $getId3 = new getID3();
                $getId3Info = $getId3->analyze($videoPath);

                return $getId3Info['video']['resolution_x'] * $getId3Info['video']['resolution_y'];
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else if(str_contains($sort, 'duration'))
            $sortedVideos = $videos->sortBy(function($video)
            {
                $videoPath = storage_path('app/public/videos/'.$video->file);

                $getId3 = new getID3();
                $getId3Info = $getId3->analyze($videoPath);

                return $getId3Info['playtime_seconds'];
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else
            return $videos;

        return $sortedVideos;
    }

    public function showUploadVideoView()
    {
        if(!Gate::allows('upload-video'))
            abort(403);

        return view('upload-video');
    }

    public function storeUploadedVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimetypes:video/mp4,video/ogg,video/webm',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1024',
            'author' => 'nullable|max:255',
            'agency' => 'nullable|max:255',
        ]);

        $file = $request->file('video');

        $videoPath = $file->store('public/videos');

        $video = Video::create([
            'file' => basename($videoPath),
            'name' => $request->name,
            'description' => $request->description,
            'platform' => $request->platform ?? null,
            'author' => $request->author ?? null,
            'agency' => $request->agency ?? null,
            'is_nsfw' => $request->isNsfw ? 1 : 0,
            'uploaded_by' => auth()->user()->id,
        ]);

        return redirect('/upload-video')->with('notification', 'Video: '.$video->name.', successfully uploaded.');
    }

    public function showVideoView(Request $request)
    {
        if(!Gate::allows('view-video'))
            abort(403);

        $video = Video::find($request->videoId);

        $videoPath = storage_path('app/public/videos/'.$video->file);

        $getId3 = new getID3();
        $getId3Info = $getId3->analyze($videoPath);

        $videoInfo = [
            'resolution' => $getId3Info['video']['resolution_x'].' x '.$getId3Info['video']['resolution_y'],
            'duration' => $getId3Info['playtime_string'],
            'bitrate' => $getId3Info['bitrate'] / 1024,
            'size' => filesize($videoPath) / 1024 / 1024,
            ];

        if(!Gate::allows('edit-videos'))
            return view('video-alt', [
                'video' => $video,
                'videoInfo' => $videoInfo,
            ]);

        return view('video', [
            'video' => $video,
            'videoInfo' => $videoInfo,
        ]);
    }

    public function downloadVideo(Request $request)
    {
        $video = Video::find($request->videoId);

        $path = storage_path('app/public/videos/'.$video->file);

        return response()->download($path);
    }

    public function updateVideo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1024'
        ]);

        $video = Video::find($request->videoId);

        $video->name = $request->name;
        $video->description = $request->description;
        $video->last_edited_by = auth()->user()->id;

        $video->save();

        session()->now('notification', 'Changes saved.');

        return $this->showVideoView($request);
    }

    public function updateVideos(Request $request)
    {
        if(!$request->selectedIds)
            return redirect()->back()->with('notification', 'You have not selected any videos.');

        $videos = Video::whereIn('id', $request->selectedIds)->get();

        return redirect('/'.$request->route)->with('videos', $videos);
    }

    public function showEditVideosView(Request $request)
    {
        if(!Gate::allows('edit-videos'))
            abort(403);

        return view('edit-videos', ['videos' => session('videos')]);
    }

    public function saveEditedVideos(Request $request)
    {
        for($i = 0; $i < count($request->ids); $i++)
        {
            $video = Video::find($request->ids[$i]);
            $video->name = $request->name[$i];
            $video->description = $request->description[$i];

            $video->save();
        }

        return redirect('/videos')->with('notification', 'Changes to '.count($request->ids).' '.Pluralizer::plural('video', count($request->ids)).' saved.');
    }

    public function deleteVideos(Request $request)
    {
        if(!Gate::allows('delete-videos'))
            abort(403);

        foreach(session('videos') as $video)
            $video->delete();

        return redirect('/videos')->with('notification', count(session('videos')).' '.Pluralizer::plural('video', count(session('videos'))).' deleted.');
    }
}
