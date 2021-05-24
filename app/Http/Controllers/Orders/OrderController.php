<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Managers\Orders\OrderManager;
use App\Models\Users\User;
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelOrder()
    {
        return response()->json($this->manager->cancelledOrder());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirmed(User $user)
    {
        return response()->json($this->manager->confirmedOrder($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function declined(User $user)
    {
        return response()->json($this->manager->declinedOrder($user));
    }
}
