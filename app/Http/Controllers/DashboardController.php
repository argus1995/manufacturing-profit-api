<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\DirectCost;
use Illuminate\Http\Request;
use App\Models\OperationalCost;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'summary'      => $this->summary(),
            'perProduct'   => $this->bep(),
            // 'bep'          => $this->bep(),
            // 'gross_profit' => $this->grossProfitWeekly(),
            // 'net_profit'   => $this->netProfitMonthly(),
        ]);
    }

    private function money($value)
    {
        return (float) $value;
    }

    private function summary()
    {
        $totalSales = $this->money(Sale::sum('total_amount'));
        $directCost = $this->money(DirectCost::sum('amount'));
        $operationalCost = $this->money(OperationalCost::sum('amount'));
        $totalCost = $directCost + $operationalCost;

        return [
            'total_sales' => $totalSales,
            'direct_cost' => $directCost,
            'operational_cost' => $operationalCost,
            'total_cost'  => $directCost + $operationalCost,
            'gross_profit' => $totalSales - $directCost,
            'net_profit'  => $totalSales - $totalCost,
        ];
    }

    private function hppPerProduct(Product $product)
    {
        $totalCost = 0;
        $totalQty  = 0;

        foreach ($product->productions as $production) {
            $totalCost += $production->directCosts->sum('amount');
            $totalQty  += $production->good_qty;
        }

        return $totalQty > 0 ? round($totalCost / $totalQty) : 0;
    }


    private function bep()
    {
        $fixedCost = OperationalCost::sum('amount');

        return Product::with(['productions.directCosts', 'saleItems'])
            ->get()
            ->map(function ($product) use ($fixedCost) {

                $hpp = $this->hppPerProduct($product);
                
                $soldQty = $product->saleItems->sum('qty');

                $sellingPrice = $product->price;
                $margin = $sellingPrice - $hpp;

                return [
                    'product' => $product->name,
                    'hpp'     => $hpp,
                    'price'   => $sellingPrice,
                    'bep_pcs' => $margin > 0 ? ceil($fixedCost / $margin) : null,
                    'sold_qty'  => $soldQty,
                ];
            });
    }


    // private function grossProfitWeekly()
    // {
    //     return Sale::selectRaw('YEARWEEK(date) as week, SUM(total) as sales')
    //         ->groupBy('week')
    //         ->get()
    //         ->map(function ($row) {
    //             $directCost = DirectCost::whereRaw('YEARWEEK(date) = ?', [$row->week])
    //                 ->sum('amount');

    //             return [
    //                 'week' => $row->week,
    //                 'gross_profit' => $row->sales - $directCost,
    //             ];
    //         });
    // }

    // private function netProfitMonthly()
    // {
    //     return Sale::selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(total) as sales')
    //         ->groupBy('year', 'month')
    //         ->get()
    //         ->map(function ($row) {
    //             $directCost = DirectCost::whereYear('date', $row->year)
    //                 ->whereMonth('date', $row->month)
    //                 ->sum('amount');

    //             $operationalCost = OperationalCost::whereYear('date', $row->year)
    //                 ->whereMonth('date', $row->month)
    //                 ->sum('amount');

    //             return [
    //                 'period' => "{$row->year}-{$row->month}",
    //                 'net_profit' => $row->sales - ($directCost + $operationalCost),
    //             ];
    //         });
    // }

}
