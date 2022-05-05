<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(InventoryRepository $repository, Request $request)
    {
        $filters = $request->input('filters');

        if($filters) {
            $filters = json_decode($filters);
        }

        $records = $repository->listForUser(
            auth()->user(),
            $request->input('records_per_page') ?? 25,
            $filters ?? []
        );

        return response()->json($records);
    }
}
