<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CartManager;
use App\Models\Product;
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
        return response()->json($this->manager->store($request, $product));
    }

    /*
    * Showing all the product inside the Cart
    * return json type result
    */
    public function show()
    {
        return response()->json($this->manager->show());
    }

    /*
    * Updating the quantity of the Product
    * inside the Cart
    * return json type result
    */
    public function update(Request $request, Product $product)
    {

        return response()->json($this->manager->update($request,  $product));
    }

    /*
    * Removing Specific Product
    * Inside the Cart
    * return json type result
    */
    public function destroy(Product $product)
    {
        return response()->json($this->manager->destroy($product));
    }
}
