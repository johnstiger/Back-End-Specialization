<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CommentManager;
use App\Models\Items\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $manager;

    public function __construct(CommentManager $manager)
    {
        $this->manager = $manager;
    }

    /*
    * Adding new Comment in the Product
    * return json type result
    */
    public function store(Request $request, Product $product)
    {

        $response = $this->manager->store($request, $product);
        return response()->json($response);
    }

    /*
    * Removing the Specific Comment
    * return json type result
    */
    public function destroy(Product $product)
    {
        $response = $this->manager->destroy($product);
        return response()->json($response);
    }
}
