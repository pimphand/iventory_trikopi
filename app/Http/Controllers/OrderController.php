<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master.orders');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($request->all());
        $start_date =  $request->date_start;
        // dd($start_date);
        $orders = Http::withToken('Bearer 7e7df13db5dda3f92672db3dffb88172')->get('https://order.qasir.id/api/v5/order/histories/web?page=1&start_date=' . $start_date . '&end_date=' . $start_date . '&settle_by=&invoice_number=&count=1000&outlet_ids=1012729&perpage=50');
        $orders = $orders->json();
        return $orders;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function syncOrder(Request $request)
    {
        $order = Http::withToken('Bearer 7e7df13db5dda3f92672db3dffb88172')->get('https://order.qasir.id/api/v5/order/histories/web?page=1&start_date=' . $request->start_date . '&end_date=' . $request->end_date . '&settle_by=&invoice_number=&count=1000&outlet_ids=1012729&perpage=50');

        $data = $order->json();

        foreach ($data['data']['sales'] as $key => $value) {

            $order =  Order::updateOrCreate(
                [
                    'date' => $value['date'],
                ],
                [
                    'total_amount' => $value['daily_amount'],
                ]
            );

            foreach ($value['items'] as $key => $item) {
                $detailOrder =  $order->details()->updateOrCreate(
                    [
                        'qasir_sales_id' => $item['sales_id'],
                    ],
                    [
                        'invoice_number' => $item['invoice_number'],
                        'amount' => $item['amount'],
                        'outlet_name' => $item['outlet_name'],
                        'settle_by' => $item['settle_by'],
                        'payment_mode' => $item['payment_mode']
                    ]
                );

                $detail = Http::withToken('Bearer 7e7df13db5dda3f92672db3dffb88172')->get('https://order.qasir.id/api/v5/order/histories/' . $item['sales_id'] . '/legacy');

                $detailData = $detail->json();

                foreach ($detailData['data']['sales']['carts'] as $key => $cart) {
                    // dd($cart);
                    OrderItem::updateOrCreate([
                        'qasir_cart_id' => $cart['id'],
                    ], [
                        'order_id' => $order->id,
                        'order_details_id' => $detailOrder->id,
                        'qasir_sales_id' => $item['sales_id'],
                        // 'product_name ' => $cart['variant']['product']['name'] ?? "po",
                        'quantity' => $cart['quantity'],
                        'price' => $cart['price_sell'],
                        'price_unit' => $cart['price_sell_unit']
                    ]);
                }
            }
        }
        return Order::with('details.items')->get();
    }
}
