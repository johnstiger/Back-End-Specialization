<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SearchEngineController extends Controller
{
    public function Customers(Request $request)
    {
        $response = [];
        try {

            $customers = User::where('is_admin',0)->where('lastname','like',"%{$request->get('data')}%")
                                                    ->orWhere('firstname','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',0)
                                                    ->orWhere('email','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',0)
                                                    ->get();
            if($customers->isEmpty()){
                $response["message"] = $request->get('data')." is not Found!";
            }else{
                $response["message"] = "Successfully search data";
                $response["data"] = $customers;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return response()->json($response);
    }

    public function Products(Request $request)
    {
        $response = [];
        try {
            $products = Product::where('name','like',"%{$request->get('data')}%")
                                ->orWhere('price','like',"%{$request->get('data')}%")
                                ->get();
            if($products->isEmpty()){
                $response["message"] = $request->get('data')." is not Found!";
            }else{
                $response["message"] = "Successfully search data";
                $response["data"] = $products;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return response()->json($response);
    }


    public function productByCategory(Request $request, Category $category)
    {
        $response = [];
        try {
            $foundData = $category->products()->where('name','like',"%{$request->get('data')}%")
                                            ->orWhere('price','like',"%{$request->get('data')}%")
                                            ->get();
            if($foundData->isEmpty()){
                $response["message"] = $request->get('data')." is not Found!";
            }else{
                $response["message"] = "Successfully search data";
                $response["data"] = $foundData;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return response()->json($response);
    }

    public function Admins(Request $request)
    {
        $response = [];
        try {

            $admins = User::where('is_admin',1)->where('lastname','like',"%{$request->get('data')}%")
                                                    ->orWhere('firstname','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',1)
                                                    ->orWhere('email','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',1)
                                                    ->get();
            if($admins->isEmpty()){
                $response["message"] = $request->get('data')." is not Found!";
            }else{
                $response["message"] = "Successfully search data";
                $response["data"] = $admins;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return response()->json($response);
    }

    public function Category(Request $request)
    {
        $response = [];
        try {
            $category = Category::where('name','like',"%{$request->get('data')}%")
                                ->get();
            if($category->isEmpty()){
                $response["message"] = $request->get('data')." is not Found!";
            }else{
                $response["message"] = "Successfully search data";
                $response["data"] = $category;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return response()->json($response);
    }
}
