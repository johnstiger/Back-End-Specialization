<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CartManager;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $manager;

    public function __construct(CartManager $manager)
    {
        $this->manager = $manager;
    }

    /*
    * Adding Product inside the Cart
    * return json type result
    */
    public function store(Request $request, Product $product)
    {
        $customer = Auth::user();
        $response = $this->manager->store($request,$customer, $product);
        return response()->json($response);
    }

    /*
    * Showing all the product inside the Cart
    * return json type result
    */
    public function show()
    {
        $customer = Auth::user();
        $response = $this->manager->show($customer);
        return response()->json($response);
    }

    /*
    * Updating the quantity of the Product
    * inside the Cart
    * return json type result
    */
    public function update(Request $request, Product $product)
    {
        $customer =Auth::user();
        $response = $this->manager->update($request, $customer, $product);
        return response()->json($response);
    }

    /*
    * Removing Specific Product
    * Inside the Cart
    * return json type result
    */
    public function destroy(Product $product)
    {
        $customer = Auth::user();
        $response = $this->manager->destroy($customer, $product);
        return response()->json($response);
    }
}
