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

        $customers = User::where('is_admin',0)->where('lastname','like',"%{$request->get('data')}%")
                                                    ->orWhere('firstname','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',0)
                                                    ->orWhere('email','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',0)
                                                    ->get();
        $response = $this->searchEngine($customers, $request->all());
        return response()->json($response);
    }

    public function Products(Request $request)
    {
        $products = Product::where('name','like',"%{$request->get('data')}%")
                                ->orWhere('price','like',"%{$request->get('data')}%")
                                ->get();
        $response = $this->searchEngine($products, $request->all());
        return response()->json($response);
    }


    public function productByCategory(Request $request, Category $category)
    {
        $foundData = $category->products()->where('name','like',"%{$request->get('data')}%")
                                            ->orWhere('price','like',"%{$request->get('data')}%")
                                            ->get();
        $response = $this->searchEngine($foundData, $request->all());

        return response()->json($response);
    }

    public function Admins(Request $request)
    {
        $admins = User::where('is_admin',1)->where('lastname','like',"%{$request->get('data')}%")
                                            ->orWhere('firstname','like',"%{$request->get('data')}%")
                                            ->where('is_admin',1)
                                            ->orWhere('email','like',"%{$request->get('data')}%")
                                            ->where('is_admin',1)
                                            ->get();
        $response = $this->searchEngine($admins, $request->all());
        return response()->json($response);
    }

    public function Category(Request $request)
    {
        $category = Category::where('name','like',"%{$request->get('data')}%")
                    ->get();

        $response = $this->searchEngine($category, $request->all());

        return response()->json($response);
    }


    public function searchEngine($request, $value)
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
