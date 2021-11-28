<?php

namespace App\Managers\Items;

use App\Managers\Template\Template;
use App\Validations\Items\CategoryValidation;
use App\Models\Category;
use App\Services\Data\DataServices;

class CategoryManager
{
    protected $template;
    protected $check;
    protected $services;

    public function __construct(Template $template, CategoryValidation $check, DataServices $services)
    {
        $this->template = $template;
        $this->check = $check;
        $this->services = $services;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->template->index($this->services->categoriesProducts());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        $response = [];

        $rules = $this->check->validation($request->all());
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $data = Category::create($request->all());
                $response["message"] = "Successfully Added ".$data->name." in Category!";
                $response["data"] = $data;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProduct($request, $category)
    {
        $response = [];
        $rules = $this->check->productValidation($request->all());
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $product = $request->all();
                $product["avail_unit_measure"] = $product["unit_measure"];
                $data = $category->products()->create($product);
                $response["message"] = "Successfully Added ".$data->name." in Product!";
                $response["data"] = $data;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        return $this->template->show($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $category)
    {
        $response = [];

        $rules = $this->check->validation($request->all());
        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $category->update($request->all());
                $response["message"] = "Successfully Updated ".$category->name;
                $response["data"] = $category;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        return $this->template->destroy($product);
    }


    public function getCategoryWithProducts($category)
    {
        return $this->template->index($this->services->getCategoryWithProducts($category->id));
    }

}



?>
