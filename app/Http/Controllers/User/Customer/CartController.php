<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CartManager;
use App\Models\Product;
use Illuminate\Http\Request;

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
        $response = $this->manager->store($request, $product);
        return response()->json($response);
    }

    /*
    * Showing all the product inside the Cart
    * return json type result
    */
    public function show()
    {
        $response = $this->manager->show();
        return response()->json($response);
    }

    /*
    * Updating the quantity of the Product
    * inside the Cart
    * return json type result
    */
    public function update(Request $request)
    {
        $response = $this->manager->update($request);
        return response()->json($response);
    }

    /*
    * Removing Specific Product
    * Inside the Cart
    * return json type result
    */
    public function destroy(Product $product)
    {
        $response = $this->manager->destroy($product);
        return response()->json($response);
    }
}
