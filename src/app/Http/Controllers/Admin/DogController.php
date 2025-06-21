<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dog;
use App\Services\DogService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DogController extends Controller
{
    public function index()
    {
        $dogs = Dog::query()->get();

        return Inertia::render('admin/dog/Dogs', [
            'dogs' => $dogs,
        ]);
    }

    public function create( DogService $dogService)
    {
        $types = $dogService->getTypes();
        $statuses = $dogService->getStatuses();

        return Inertia::render('admin/dog/DogCreate', [
            'types' => $types,
            'statuses' => $statuses,
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
