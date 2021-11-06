<?php

namespace App\Managers\Guest;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SearchManager
{

    public function Customers($request)
    {
        $customers = User::where('is_admin',0)->where('lastname','like',"%{$request->get('data')}%")
                                                    ->orWhere('firstname','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',0)
                                                    ->orWhere('email','like',"%{$request->get('data')}%")
                                                    ->where('is_admin',0)
                                                    ->get();
        return $this->template($customers, $request);
    }

    public function Products($request)
    {
        $products = Product::where('name','like',"%{$request->get('data')}%")
                                ->orWhere('price','like',"%{$request->get('data')}%")->with(['category','sizes'])
                                ->get();
        return $this->template($products, $request);
    }

    public function productByCategory($request, $category)
    {
        $foundData = $category->products()->where('name','like',"%{$request->get('data')}%")
                                            ->orWhere('price','like',"%{$request->get('data')}%")
                                            ->get();
        return $this->template($foundData, $request);
    }

    public function Admins($request)
    {
        $user = Auth::user();
        $admins = User::where('is_admin',1)->where('lastname','like',"%{$request->get('data')}%")->where('email','!=',$user->email)
                                            ->orWhere('firstname','like',"%{$request->get('data')}%")
                                            ->where('is_admin',1)->where('email','!=',$user->email)
                                            ->orWhere('email','like',"%{$request->get('data')}%")
                                            ->where('is_admin',1)->where('email','!=',$user->email)
                                            ->get();
        return $this->template($admins, $request);
    }

    public function Category($request)
    {
        $category = Category::where('name','like',"%{$request->get('data')}%")
                    ->get();
        return $this->template($category, $request);
    }

    public function Orders($request)
    {
        // $orders = Order::where('')
    }

    public function template($request, $value)
    {
        $response = [];
        try {
            if($request->isEmpty()){
                $response["message"] = $value->get('data')." is not Found";
                $response["found"] = false;
            }else{
                $response["message"] = "Successfully search data";
                $response["data"] = $request;
                $response["found"] = true;
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
