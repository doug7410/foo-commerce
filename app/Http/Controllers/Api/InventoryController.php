<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $repo = new InventoryRepository();
        $filters = $request->input('filters');

        if($filters) {
            $filters = json_decode($filters);
        }

        $records = $repo->listForUser(
            auth()->user(),
            $request->input('records_per_page') ?? 25,
            $filters ?? []
        );

        return response()->json($records);
    }
}
