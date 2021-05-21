<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Managers\Items\CategoryManager;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $manager;

    public function __construct(CategoryManager $manager)
    {
        $this->manager = $manager;
    }

    /*
    * Displaying all the catergories with products
    * return json type result
    */
    public function index()
    {
        $response = $this->manager->index();
        return response()->json($response);
    }

    /*
    * Adding new Category
    * return json type result
    */
    public function store(Request $request)
    {
        $response = $this->manager->store($request);
        return response()->json($response);
    }

    /*
    * Adding Product in Specified Category
    * return json type result
    */
    public function storeProduct(Request $request, Category $category)
    {
        $response = $this->manager->storeProduct($request, $category);
        return response()->json($response);
    }

    /*
    * Showing Specific Category
    * return json type result
    */
    public function show(Category $category)
    {
        $response = $this->manager->show($category);
        return response()->json($response);
    }

    /*
    * Updating the Category
    * return json type result
    */
    public function update(Request $request, Category $category)
    {
        $response = $this->manager->update($request, $category);
        return response()->json($response);
    }

    /*
    * Removing Category with SoftDelete
    * return json type result
    */
    public function destroy(Category $category)
    {
        $response = $this->manager->destroy($category);
        return response()->json($response);
    }


}
