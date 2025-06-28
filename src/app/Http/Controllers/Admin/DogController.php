<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dog\CreateDogRequest;
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

    public function store(CreateDogRequest $request)
    {
        dd($request->all());
    }
}
