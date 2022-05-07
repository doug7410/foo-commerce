<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(InventoryRepository $repository, Request $request)
    {
        $records = $repository->listForUser(
            auth()->user(),
            $request->input('records_per_page') ?? 25,
            $request->filters
        );

        return response()->json($records);
    }
}
