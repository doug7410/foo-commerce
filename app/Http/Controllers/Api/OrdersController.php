<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrdersRepository;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(OrdersRepository $repository)
    {

        $orders = $repository->listForUser(auth()->user());
        return response()->json($orders);
    }
}
