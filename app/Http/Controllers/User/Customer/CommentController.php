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

        return response()->json($this->manager->store($request, $product));
    }

    /*
    * Removing the Specific Comment
    * return json type result
    */
    public function destroy(Product $product)
    {
        return response()->json($this->manager->destroy($product));
    }
}
