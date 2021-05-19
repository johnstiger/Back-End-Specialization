<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CommentManager;
use App\Models\Product;
use App\Models\User;
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
    public function store(Request $request, User $customer, Product $product)
    {
       $response = $this->manager->store($request, $customer, $product);
        return response()->json($response);
    }

    /*
    * Removing the Specific Comment
    * return json type result
    */
    public function destroy(User $customer, Product $product)
    {
        $response = $this->manager->destroy($customer, $product);
        return response()->json($response);
    }
}
