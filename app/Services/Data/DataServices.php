<?php

namespace App\Services\Data;

use App\Models\Items\Category;
use App\Models\Items\Product;
use App\Models\Users\User;

class DataServices
{
    public function getUser($request)
    {
        return User::where('email',$request->email)->first();
    }

    public function allCustomers()
    {
        return User::where('is_admin',0)->get();
    }

    public function allAdmins()
    {
        return User::where('is_admin',1)->get();
    }

    public function getAdmin($request)
    {
        return User::where('is_admin',1)->where('email',$request->email)->first();
    }

    public function allProducts()
    {
        return Product::where('status',1)->with('sizes')->get();
    }

    public function allCategory()
    {
        return Category::with('products')->get();
    }

    public function createUser($request)
    {
        return User::create($request);
    }

    public function createProduct($request)
    {
        return Product::create($request);
    }

    public function createCategory($request)
    {
        return Category::create($request);
    }

}



?>
