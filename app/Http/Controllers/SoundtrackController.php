<?php

namespace App\Http\Controllers;

use getID3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Soundtrack;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Collection;

class SoundtrackController extends Controller
{
    public function showSoundtracksView(Request $request)
    {
        if(!Gate::allows('view-soundtracks'))
            abort(403);

        $isFilterPanelOpen = false;

        if($request->action && $request->action == 'clear search')
            $search = null;
        else
            $search = $request->search ?? null;

        if($request->action && $request->action == 'reset filters')
            $filters = [
                'dateFrom' => null,
                'dateTo' => null,
                'nsfw' => false,
            ];
        else
            $filters = [
                'dateFrom' => $request->dateFrom ?? null,
                'dateTo' => $request->dateTo ?? null,
                'nsfw' => $request->nsfw ?? false,
            ];

        if($this->areSoundtracksFiltered($filters))
            $isFilterPanelOpen = true;

        if($search || $this->areSoundtracksFiltered($filters))
            $soundtracks = $this->getSoundtracksFiltered($search, $filters);
        else
            $soundtracks = Soundtrack::all();

        $sort = $request->sort ?? 'upload date';

        return view('soundtracks', [
            'isFilterPanelOpen' => $isFilterPanelOpen,
            'search' => $search,
            'filters' => $filters,
            'sort' => $sort,
            'soundtracks' => $this->sortSoundtracks($soundtracks, $sort),
            'results' => count($soundtracks).' '.Pluralizer::plural('result', count($soundtracks)),
        ]);
    }

    private function areSoundtracksFiltered($filters): bool
    {
        return $filters['dateFrom'] || $filters['dateTo'] || $filters['nsfw'];
    }

    private function getSoundtracksFiltered($search, $filters): Collection
    {
        $soundtracks = [];

        foreach(Soundtrack::all() as $soundtrack)
            if($this->soundtrackMatchesSearch($soundtrack, $search) && $this->soundtrackMatchesFilters($soundtrack, $filters))
                array_push($soundtracks, $soundtrack);

        return collect($soundtracks);
    }

    private function soundtrackMatchesSearch($soundtrack, $search): bool
    {
        if($search
           && !str_contains(mb_strtolower($soundtrack->name, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($soundtrack->description, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($soundtrack->author, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($soundtrack->uploadedBy->userDetails->first_name, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($soundtrack->uploadedBy->userDetails->last_name, 'UTF-8'), mb_strtolower($search, 'UTF-8')))
            return false;

        return true;
    }

    private function soundtrackMatchesFilters($soundtrack, $filters): bool
    {
        if($filters['dateFrom'] && strtotime($soundtrack->created_at) < strtotime($filters['dateFrom']))
            return false;

        if($filters['dateTo'] && strtotime($soundtrack->created_at) > strtotime($filters['dateTo']))
            return false;

        if($filters['nsfw'] && $soundtrack->is_nsfw)
            return false;

        return true;
    }

    private function sortSoundtracks($soundtracks, $sort): Collection
    {
        if(str_contains($sort, 'upload date'))
            $sortedSoundtracks = $soundtracks->sortBy(function($soundtrack)
            {
                return strtotime($soundtrack->created_at);
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else if(str_contains($sort, 'duration'))
            $sortedSoundtracks = $soundtracks->sortBy(function($soundtrack)
            {
                $soundtrackPath = storage_path('app/public/soundtracks/'.$soundtrack->file);

                $getId3 = new getID3();
                $getId3Info = $getId3->analyze($soundtrackPath);

                return $getId3Info['playtime_seconds'];
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else
            return $soundtracks;

        return $sortedSoundtracks;
    }

    public function showUploadSoundtrackView()
    {
        if(!Gate::allows('upload-soundtrack'))
            abort(403);

        return view('upload-soundtrack');
    }

    public function storeUploadedSoundtrack(Request $request)
    {
        $request->validate([
            'soundtrack' => 'required|mimetypes:audio/mpeg,audio/ogg,audio/wav',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1024',
            'author' => 'nullable|max:255',
        ]);

        $file = $request->file('soundtrack');

        $soundtrackPath = $file->store('public/soundtracks');

        $soundtrack = Soundtrack::create([
            'file' => basename($soundtrackPath),
            'name' => $request->name,
            'description' => $request->description,
            'author' => $request->author ?? null,
            'is_nsfw' => $request->isNsfw ? 1 : 0,
            'uploaded_by' => auth()->user()->id,
        ]);

        return redirect('/upload-soundtrack')->with('notification', 'Soundtrack: '.$soundtrack->name.', successfully uploaded.');
    }

    public function showSoundtrackView(Request $request)
    {
        if(!Gate::allows('view-soundtrack'))
            abort(403);

        $soundtrack = Soundtrack::find($request->soundtrackId);

        $soundtrackPath = storage_path('app/public/soundtracks/'.$soundtrack->file);

        $getId3 = new getID3();
        $getId3Info = $getId3->analyze($soundtrackPath);

        $soundtrackInfo = [
            'duration' => $getId3Info['playtime_string'],
            'bitrate' => $getId3Info['bitrate'] / 1024,
            'size' => filesize($soundtrackPath) / 1024 / 1024,
        ];

        if(!Gate::allows('edit-soundtracks'))
            return view('soundtrack-alt', [
                'soundtrack' => $soundtrack,
                'soundtrackInfo' => $soundtrackInfo,
            ]);

        return view('soundtrack', [
            'soundtrack' => $soundtrack,
            'soundtrackInfo' => $soundtrackInfo,
        ]);
    }

    public function downloadSoundtrack(Request $request)
    {
        $soundtrack = Soundtrack::find($request->soundtrackId);

        $path = storage_path('app/public/soundtracks/'.$soundtrack->file);

        return response()->download($path);
    }

    public function updateSoundtrack(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1024'
        ]);

        $soundtrack = Soundtrack::find($request->soundtrackId);

        $soundtrack->name = $request->name;
        $soundtrack->description = $request->description;
        $soundtrack->last_edited_by = auth()->user()->id;

        $soundtrack->save();

        session()->now('notification', 'Changes saved.');

        return $this->showSoundtrackView($request);
    }

    public function updateSoundtracks(Request $request)
    {
        if(!$request->selectedIds)
            return redirect()->back()->with('notification', 'You have not selected any soundtracks.');

        $soundtracks = Soundtrack::whereIn('id', $request->selectedIds)->get();

        return redirect('/'.$request->route)->with('soundtracks', $soundtracks);
    }

    public function showEditSoundtracksView(Request $request)
    {
        if(!Gate::allows('edit-soundtracks'))
            abort(403);

        return view('edit-soundtracks', ['soundtracks' => session('soundtracks')]);
    }

    public function saveEditedSoundtracks(Request $request)
    {
        for($i = 0; $i < count($request->ids); $i++)
        {
            $soundtrack = Soundtrack::find($request->ids[$i]);
            $soundtrack->name = $request->name[$i];
            $soundtrack->description = $request->description[$i];

            $soundtrack->save();
        }

        return redirect('/soundtracks')->with('notification', 'Changes to '.count($request->ids).' '.Pluralizer::plural('soundtrack', count($request->ids)).' saved.');
    }

    public function deleteSoundtracks(Request $request)
    {
        if(!Gate::allows('delete-soundtracks'))
            abort(403);

        foreach(session('soundtracks') as $soundtrack)
            $soundtrack->delete();

        return redirect('/soundtracks')->with('notification', count(session('soundtracks')).' '.Pluralizer::plural('soundtrack', count(session('soundtracks'))).' deleted.');
    }
}
