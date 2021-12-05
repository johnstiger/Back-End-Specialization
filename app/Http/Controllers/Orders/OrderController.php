<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Managers\Orders\OrderManager;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $manager;
    public function __construct(OrderManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->manager->index();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingOrders()
    {
        return $this->manager->pendingOrders();
    }


    public function getNotification()
    {
        return response()->json($this->manager->notification());
    }

    public function viewPendingOrders()
    {
        return response()->json($this->manager->updatePendingOrder());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmOrder(Request $request, User $user)
    {
        return $this->manager->orderConfirmed($request, $user);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function declinedOrder(Request $request, User $user)
    {
        return $this->manager->declinedOrder($request, $user);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json($this->manager->create());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return response()->json($this->manager->store($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json($this->manager->show());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addingTrackingCode(Request $request, User $user)
    {
        return response()->json($this->manager->addTrackingCode($request, $user));
    }


    public function receivedOrder(Order $order)
    {
        return response()->json($this->manager->received($order));
    }

}
