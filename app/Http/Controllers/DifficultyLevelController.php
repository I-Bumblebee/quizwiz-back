<?php

namespace App\Http\Controllers;

use App\Models\DifficultyLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DifficultyLevelController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(DifficultyLevel::all());
    }
}
