<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Managers\Items\SalesItemManager;
use App\Models\Product;
use App\Models\SalesItem;
use Illuminate\Http\Request;

class SalesItemController extends Controller
{

    private $data;

    public function __construct(SalesItemManager $data)
    {
        $this->data = $data;
    }
    /*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->data->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        return response()->json($this->data->store($request, $product));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show(SalesItem $salesItem)
    // {
    //     return response()->json($this->data->show($salesItem));
    // }
    public function show(Product $salesItem)
    {
        return response()->json($this->data->show($salesItem));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, SalesItem $salesItem)
    // {
    //     return response()->json($this->data->update($request, $salesItem));
    // }
    public function update(Request $request, Product $salesItem)
    {
        return response()->json($this->data->update($request, $salesItem));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(SalesItem $salesItem)
    // {
    //     return response()->json($this->data->delete($salesItem));
    // }
    public function destroy(Product $salesItem)
    {
        return response()->json($this->data->delete($salesItem));
    }
}
