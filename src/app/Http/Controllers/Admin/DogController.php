<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dog;
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

    public function create()
    {

        return Inertia::render('admin/dog/DogCreate');
    }
}
