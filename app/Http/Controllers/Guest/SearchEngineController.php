<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Managers\Guest\SearchManager;
use App\Models\Items\Category;
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
        return response()->json($this->search->Customers($request));
    }

    /*
    * Search All Products
    * return json type result
    */
    public function Products(Request $request)
    {
        return response()->json($this->search->Products($request));
    }

    /*
    * Search All Products in Category Data
    * return json type result
    */
    public function productByCategory(Request $request, Category $category)
    {
        return response()->json($this->search->productByCategory($request, $category));
    }

    /*
    * Search All Admins
    * return json type result
    */
    public function Admins(Request $request)
    {
        return response()->json($this->search->Admins($request));
    }

    /*
    * Search All Category
    * return json type result
    */
    public function Category(Request $request)
    {
        return response()->json($this->search->Category($request));
    }

}
