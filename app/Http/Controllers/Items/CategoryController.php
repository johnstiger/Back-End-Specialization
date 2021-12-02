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
        return response()->json($this->manager->index());
    }

    public function getCategories()
    {
        return response()->json($this->manager->index());
    }

    /*
    * Adding new Category
    * return json type result
    */
    public function store(Request $request)
    {
        return response()->json($this->manager->store($request));
    }

    /*
    * Adding Product in Specified Category
    * return json type result
    */
    public function storeProduct(Request $request, Category $category)
    {
        return response()->json($this->manager->storeProduct($request, $category));
    }

    /*
    * Showing Specific Category
    * return json type result
    */
    public function show(Category $category)
    {
        return response()->json($this->manager->show($category));
    }

    /*
    * Updating the Category
    * return json type result
    */
    public function update(Request $request, Category $category)
    {
        return response()->json($this->manager->update($request, $category));
    }

    /*
    * Removing Category with SoftDelete
    * return json type result
    */
    public function destroy(Category $category)
    {
        return response()->json($this->manager->destroy($category));
    }


    /**
     * Customer Side
     */

     public function getCategory(Category $category)
     {
         return response()->json($this->manager->getCategoryWithProducts($category));
     }


}
