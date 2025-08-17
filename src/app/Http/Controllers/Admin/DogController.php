<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dog\CreateDogRequest;
use App\Http\Requests\Dog\UpdateDogRequest;
use App\Http\Resources\Dog\DogItemResource;
use App\Http\Resources\Dog\DogResource;
use App\Http\Resources\Dog\ImageResource;
use App\Models\Dog;
use App\Models\DogImage;
use App\Services\DogImageService;
use App\Services\DogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DogController extends Controller
{

    public function index(): Response
    {
        $dogs = Dog::query()->get();

        return Inertia::render('admin/dog/Dogs', [
            'dogs' => DogItemResource::collection($dogs),
        ]);
    }

    public function create(DogService $dogService): Response
    {
        $genders = $dogService->getGenders();
        $types = $dogService->getTypes();
        $statuses = $dogService->getStatuses();
        $dogs = $dogService->getDogs();

        return Inertia::render('admin/dog/DogCreate', [
            'types' => $types,
            'genders' => $genders,
            'statuses' => $statuses,
            'dogs' => $dogs,
        ]);
    }

    /**
     * @param CreateDogRequest $request
     * @param DogService $dogService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateDogRequest $request, DogService $dogService)
    {
        $dog = $dogService->create($request->validated());

        return redirect()->route('admin.dog.edit', parameters: ['id' => $dog->id ]);
    }

    /**
     * @param int $id
     * @param DogService $dogService
     * @return Response
     */
    public function edit(int $id, DogService $dogService): Response
    {
        $dog = Dog::query()->with(['images'])->findOrFail($id);
        $genders = $dogService->getGenders();
        $types = $dogService->getTypes();
        $statuses = $dogService->getStatuses();
        $dogs = $dogService->getDogs();

        return Inertia::render('admin/dog/DogEdit', [
            'dog' => DogResource::make($dog),
            'genders' => $genders,
            'types' => $types,
            'statuses' => $statuses,
            'dogs' => $dogs,
        ]);
    }

    public function update(int $id, UpdateDogRequest $request, DogService $dogService)
    {
        $dog = $dogService->update($id, $request->validated());

        return redirect()->route('admin.dog.edit', parameters: ['id' => $dog->id ]);
    }

    public function deleteImage(int $id, DogService $dogService)
    {
        $dogService->deleteImage($id);

        return response()->json([
            'message' => 'Изображение успешно удалено.',
        ]);
    }

    public function deleteGalleryImage(int $id, DogImageService $dogImageService)
    {
        $dogImage = DogImage::query()->findOrFail($id);
        $dogId = $dogImage->dog_id;
        $dogImageService->delete($id);
        $images = DogImage::query()->where('dog_id', $dogId)->get();

        return response()->json([
            'message' => 'Изображение успешно удалено.',
            'images' => ImageResource::collection($images),
        ]);
    }
}
