<?php

namespace App\Managers\Guest;

use App\Models\Items\Category;
use App\Models\Items\Product;
use App\Models\Users\User;

class SearchManager
{
    /**
     * Searching All Customers
     * return json
     */
    public function Customers($request)
    {
        $customers = User::where('is_admin',0)->where('lastname','like',"%{$request->get('data')}%")
                                                    ->orWhere('firstname','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',0)
                                                    ->orWhere('email','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',0)
                                                    ->get();
        return $this->template($customers, $request->all());
    }

    /**
     * Searching All Products
     * return json
     */
    public function Products($request)
    {
        $products = Product::where('name','like',"%{$request->get('data')}%")
                                ->orWhere('price','like',"%{$request->get('data')}%")
                                ->get();
        return $this->template($products, $request->all());
    }

    /**
     * Searching All Product by Category
     * return json
     */
    public function productByCategory($request, $category)
    {
        $foundData = $category->products()->where('name','like',"%{$request->get('data')}%")
                                            ->orWhere('price','like',"%{$request->get('data')}%")
                                            ->get();
        return $this->template($foundData, $request->all());
    }

    /**
     * Searching All Admins
     * return json
     */
    public function Admins($request)
    {
        $admins = User::where('is_admin',1)->where('lastname','like',"%{$request->get('data')}%")
                                            ->orWhere('firstname','like',"%{$request->get('data')}%")
                                            ->where('is_admin',1)
                                            ->orWhere('email','like',"%{$request->get('data')}%")
                                            ->where('is_admin',1)
                                            ->get();
        return $this->template($admins, $request->all());
    }

    /**
     * Searching All Category
     * return json
     */
    public function Category($request)
    {
        $category = Category::where('name','like',"%{$request->get('data')}%")
                    ->get();
        return $this->template($category, $request->all());
    }

    /**
     * Template in formating the response
     * return json
     */
    public function template($request, $value)
    {
        $response = [];
        try {
            if($request->isEmpty()){
                $response["message"] = $value->get('data')." is not Found";
            }else{
                $response["message"] = "Successfully search data";
                $response["data"] = $request;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }


}


?>
