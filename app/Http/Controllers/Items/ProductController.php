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
        return response()->json($this->manager->index());
    }

    public function indexOther()
    {
        return response()->json($this->manager->index(true));
    }

    public function sizes()
    {
        return response()->json($this->manager->getSizes());
    }


    /*
    * Adding new Product
    * return json type result
    */
    public function store(Request $request)
    {
        return response()->json($this->manager->store($request));
    }

    /*
    * Adding new Product Image
    * return json type result
    */
    public function savingImage(Request $request, Product $product)
    {
        return response()->json($this->manager->putImage($request, $product));
    }

    /*
    * Showing Specific Product
    * return json type result
    */
    public function show(Product $product)
    {
        return response()->json($this->manager->show($product));
    }


    /*
    * Updating Product's information
    * return json type result
    */
    public function update(Request $request, Product $product)
    {
        return response()->json($this->manager->update($request, $product));
    }

    /*
    * Removing Specific Product with SoftDelete
    * return json type result
    */
    public function destroy(Product $product)
    {
        return response()->json($this->manager->destroy($product));
    }


}
