<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CartManager;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
    public function store(Request $request, User $customer, Product $product)
    {
        $response = $this->manager->store($request, $customer, $product);
        return response()->json($response);
    }

    /*
    * Showing all the product inside the Cart
    * return json type result
    */
    public function show(User $customer)
    {
        $response = $this->manager->show($customer);
        return response()->json($response);
    }

    /*
    * Updating the quantity of the Product
    * inside the Cart
    * return json type result
    */
    public function update(Request $request, User $customer, Product $product)
    {
        $response = $this->manager->update($request, $customer, $product);
        return response()->json($response);
    }

    /*
    * Removing Specific Product
    * Inside the Cart
    * return json type result
    */
    public function destroy(User $customer, Product $product)
    {
        $response = $this->manager->destroy($customer, $product);
        return response()->json($response);
    }
}
