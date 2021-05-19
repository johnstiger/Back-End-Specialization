<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Managers\Items\ProductManager;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $manager;

    public function __construct(ProductManager $manager)
    {
        $this->manager = $manager;
    }

    /*
    * Displaying All the Products
    * return json type result
    */
    public function index()
    {
        $response = $this->manager->index();
        return response()->json($response);
    }


    /*
    * Adding new Product
    * return json type result
    */
    public function store(Request $request)
    {
        $response = $this->manager->store($request);
        return response()->json($response);
    }

    /*
    * Showing Specific Product
    * return json type result
    */
    public function show(Product $product)
    {
        $response = $this->manager->show($product);
        return response()->json($response);
    }


    /*
    * Updating Product's information
    * return json type result
    */
    public function update(Request $request, Product $product)
    {
        $response = $this->manager->update($request, $product);
        return response()->json($response);
    }

    /*
    * Removing Specific Product with SoftDelete
    * return json type result
    */
    public function destroy(Product $product)
    {
        $response = $this->manager->destroy($product);
        return response()->json($response);
    }


}
