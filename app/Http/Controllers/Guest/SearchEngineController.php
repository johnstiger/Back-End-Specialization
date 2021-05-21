<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Managers\Guest\SearchManager;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchEngineController extends Controller
{
    protected $search;
    public function __construct(SearchManager $search)
    {
        $this->search = $search;
    }

    /*
    * Search All Cusotmers
    * return json type result
    */
    public function Customers(Request $request)
    {
        $response = $this->search->Customers($request);
        return response()->json($response);
    }

    /*
    * Search All Products
    * return json type result
    */
    public function Products(Request $request)
    {
        $response = $this->search->Products($request);
        return response()->json($response);
    }

    /*
    * Search All Products in Category Data
    * return json type result
    */
    public function productByCategory(Request $request, Category $category)
    {
        $response = $this->search->productByCategory($request, $category);
        return response()->json($response);
    }

    /*
    * Search All Admins
    * return json type result
    */
    public function Admins(Request $request)
    {
        $response = $this->search->Admins($request);
        return response()->json($response);
    }

    /*
    * Search All Category
    * return json type result
    */
    public function Category(Request $request)
    {
        $response = $this->search->Category($request);
        return response()->json($response);
    }

}
