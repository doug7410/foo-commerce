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

        $filters = $request->input('filters');

        if($filters) {
            $filters = json_decode($filters, true);
        }

        $orders = $repository->listForUser(
            $user,
            $request->input('records_per_page') ?? 25,
            $filters ?? []
        );

        return response()->json([
            'paginated_orders' => $orders,
            'filtered_total_sales' => $filters ? $repository->filteredTotalSalesForUser($user, $filters) : null,
            'filtered_average_sale' => $filters ? $repository->filteredAverageSaleForUser($user, $filters) : null,
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
