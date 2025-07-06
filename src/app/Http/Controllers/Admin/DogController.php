<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dog\CreateDogRequest;
use App\Http\Requests\Dog\UpdateDogRequest;
use App\Http\Resources\Dog\DogResource;
use App\Models\Dog;
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
            'dogs' => $dogs,
        ]);
    }

    public function create(DogService $dogService): Response
    {
        $types = $dogService->getTypes();
        $statuses = $dogService->getStatuses();
        $dogs = $dogService->getDogs();

        return Inertia::render('admin/dog/DogCreate', [
            'types' => $types,
            'statuses' => $statuses,
            'dogs' => $dogs,
        ]);
    }

    public function store(CreateDogRequest $request, DogService $dogService)
    {
        $dog = $dogService->create($request->validated());

        return redirect()->route('admin.dog.edit', parameters: ['id' => $dog->id ]);
    }

    public function edit(int $id, DogService $dogService): Response
    {
        $dog = Dog::query()->findOrFail($id);
        $types = $dogService->getTypes();
        $statuses = $dogService->getStatuses();
        $dogs = $dogService->getDogs();

        return Inertia::render('admin/dog/DogEdit', [
            'dog' => DogResource::make($dog),
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
}
