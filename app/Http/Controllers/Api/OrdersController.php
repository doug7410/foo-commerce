<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\OrdersRepository;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(OrdersRepository $repository, Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $orders = $repository->listForUser(
            $user,
            $request->input('records_per_page') ?? 25,
            $request->filters
        );

        return response()->json([
            'paginated_orders' => $orders,
            'filtered_total_sales' => $request->filters ? $repository->filteredTotalSalesForUser($user, $request->filters) : null,
            'filtered_average_sale' => $request->filters ? $repository->filteredAverageSaleForUser($user, $request->filters) : null,
        ]);
    }

    public function salesReport(OrdersRepository $repository)
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            'total_sales' => $repository->totalSalesForUser($user),
            'average_sale' => $repository->averageSaleForUser($user),
            'breakdown' => $repository->orderBreakdownForUser($user),
        ]);
    }
}
