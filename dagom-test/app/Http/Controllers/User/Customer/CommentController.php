<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CommentManager;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $customer = Auth::user();
       $response = $this->manager->store($request, $customer, $product);
        return response()->json($response);
    }

    /*
    * Removing the Specific Comment
    * return json type result
    */
    public function destroy(Product $product)
    {
        $customer = Auth::user();
        $response = $this->manager->destroy($customer, $product);
        return response()->json($response);
    }
}
