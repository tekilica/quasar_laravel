<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Image;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Collection;

class ImageController extends Controller
{
    public function showImagesView(Request $request)
    {
        if(!Gate::allows('view-images'))
            abort(403);

        $isFilterPanelOpen = false;

        if($request->action && $request->action == 'clear search')
            $search = null;
        else
            $search = $request->search ?? null;

        if($request->action && $request->action == 'reset filters')
            $filters = [
                'printScreen' => null,
                'resolutionCriteria' => 'at least',
                'width' => null,
                'height' => null,
                'dateFrom' => null,
                'dateTo' => null,
                'nsfw' => false,
            ];
        else
            $filters = [
                'printScreen' => $request->printScreen ?? null,
                'resolutionCriteria' => $request->resolutionCriteria,
                'width' => $request->width ?? null,
                'height' => $request->height ?? null,
                'dateFrom' => $request->dateFrom ?? null,
                'dateTo' => $request->dateTo ?? null,
                'nsfw' => $request->nsfw ?? false,
            ];

        if($this->areImagesFiltered($filters))
            $isFilterPanelOpen = true;

        if($search || $this->areImagesFiltered($filters))
            $images = $this->getImagesFiltered($search, $filters);
        else
            $images = Image::all();

        $sort = $request->sort ?? 'upload date';

        return view('images', [
            'isFilterPanelOpen' => $isFilterPanelOpen,
            'search' => $search,
            'filters' => $filters,
            'sort' => $sort,
            'images' => $this->sortImages($images, $sort),
            'results' => count($images).' '.Pluralizer::plural('result', count($images)),
        ]);
    }

    private function areImagesFiltered($filters): bool
    {
        return $filters['printScreen'] || $filters['width'] || $filters['height'] || $filters['dateFrom'] || $filters['dateTo'] || $filters['nsfw'];
    }

    private function getImagesFiltered($search, $filters): Collection
    {
        $images = [];

        foreach(Image::all() as $image)
            if($this->imageMatchesSearch($image, $search) && $this->imageMatchesFilters($image, $filters))
                array_push($images, $image);

        return collect($images);
    }

    private function imageMatchesSearch($image, $search): bool
    {
        if($search
           && !str_contains(mb_strtolower($image->name, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($image->description, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($image->author, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($image->agency, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($image->uploadedBy->userDetails->first_name, 'UTF-8'), mb_strtolower($search, 'UTF-8'))
           && !str_contains(mb_strtolower($image->uploadedBy->userDetails->last_name, 'UTF-8'), mb_strtolower($search, 'UTF-8')))
            return false;

        return true;
    }

    private function imageMatchesFilters($image, $filters): bool
    {
        if($filters['printScreen'])
        {
            if($filters['printScreen'] == 'None')
            {
                if($image->print_screen)
                    return false;
            }
            else if($filters['printScreen'] == 'Any')
            {
                if(!$image->print_screen)
                    return false;
            }
            else
            {
                if($image->print_screen != $filters['printScreen'])
                    return false;
            }
        }

        $imagePath = storage_path('app/public/images/'.$image->file);

        $imageSizeData = getimagesize($imagePath);

        if($filters['resolutionCriteria'] == 'at least')
        {
            if($filters['width'] && $imageSizeData[0] < $filters['width'])
                return false;

            if($filters['height'] && $imageSizeData[1] < $filters['height'])
                return false;
        }
        else if($filters['resolutionCriteria'] == 'exactly')
        {
            if($filters['width'] && $imageSizeData[0] != $filters['width'])
                return false;

            if($filters['height'] && $imageSizeData[1] != $filters['height'])
                return false;
        }

        if($filters['dateFrom'] && strtotime($image->created_at) < strtotime($filters['dateFrom']))
            return false;

        if($filters['dateTo'] && strtotime($image->created_at) > strtotime($filters['dateTo']))
            return false;

        if($filters['nsfw'] && $image->is_nsfw)
            return false;

        return true;
    }

    private function sortImages($images, $sort): Collection
    {
        if(str_contains($sort, 'upload date'))
            $sortedImages = $images->sortBy(function($image)
            {
                return strtotime($image->created_at);
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else if(str_contains($sort, 'resolution'))
            $sortedImages = $images->sortBy(function($image)
            {
                $imagePath = storage_path('app/public/images/'.$image->file);

                $imageSizeData = getimagesize($imagePath);

                return $imageSizeData[0] * $imageSizeData[1];
            }, SORT_REGULAR, str_contains($sort, 'desc'));
        else
            return $images;

        return $sortedImages;
    }

    public function showUploadImagesView()
    {
        if(!Gate::allows('upload-images'))
            abort(403);

        return view('upload-images');
    }

    public function storeUploadedImages(Request $request)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|max:32768',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1024',
            'author' => 'nullable|max:255',
            'agency' => 'nullable|max:255',
        ]);

        $files = $request->file('images');

        foreach($files as $file)
        {
            $path = $file->store('public/images');

            $image = Image::create([
                'file' => basename($path),
                'name' => $request->name,
                'description' => $request->description,
                'print_screen' => $request->printScreen ?? null,
                'author' => $request->author ?? null,
                'agency' => $request->agency ?? null,
                'is_nsfw' => $request->isNsfw ? 1 : 0,
                'uploaded_by' => auth()->user()->id,
            ]);
        }

        return redirect('/upload-images')->with('notification', count($files).' '.Pluralizer::plural('image', count($files)).' successfully uploaded.');
    }

    public function showImageView(Request $request)
    {
        if(!Gate::allows('view-image'))
            abort(403);

        $image = Image::find($request->imageId);

        $path = storage_path('app/public/images/'.$image->file);

        $imageSizeData = getimagesize($path);

        $imageInfo = [
            'resolution' => $imageSizeData[0].' x '.$imageSizeData[1],
            'size' => filesize($path) / 1024 / 1024,
            ];

        if(!Gate::allows('edit-images'))
            return view('image-alt', [
                'image' => $image,
                'imageInfo' => $imageInfo,
            ]);

        return view('image', [
            'image' => $image,
            'imageInfo' => $imageInfo,
        ]);
    }

    public function downloadImage(Request $request)
    {
        $image = Image::find($request->imageId);

        $path = storage_path('app/public/images/'.$image->file);

        return response()->download($path);
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1024'
        ]);

        $image = Image::find($request->imageId);

        $image->name = $request->name;
        $image->description = $request->description;
        $image->last_edited_by = auth()->user()->id;

        $image->save();

        session()->now('notification', 'Changes saved.');

        return $this->showImageView($request);
    }

    public function updateImages(Request $request)
    {
        if(!$request->selectedIds)
            return redirect()->back()->with('notification', 'You have not selected any images.');

        $images = Image::whereIn('id', $request->selectedIds)->get();

        return redirect('/'.$request->route)->with('images', $images);
    }

    public function showEditImagesView(Request $request)
    {
        if(!Gate::allows('edit-images'))
            abort(403);

        return view('edit-images', ['images' => session('images')]);
    }

    public function saveEditedImages(Request $request)
    {
        for($i = 0; $i < count($request->ids); $i++)
        {
            $image = Image::find($request->ids[$i]);
            $image->name = $request->name[$i];
            $image->description = $request->description[$i];

            $image->save();
        }

        return redirect('/images')->with('notification', 'Changes to '.count($request->ids).' '.Pluralizer::plural('image', count($request->ids)).' saved.');
    }

    public function deleteImages(Request $request)
    {
        if(!Gate::allows('delete-images'))
            abort(403);

        foreach(session('images') as $image)
            $image->delete();

        return redirect('/images')->with('notification', count(session('images')).' '.Pluralizer::plural('image', count(session('images'))).' deleted.');
    }
}
