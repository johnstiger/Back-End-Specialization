<?php

namespace App\Managers\Items;

use App\Managers\Template\Template;
use App\Validations\Items\ProductValidation as ItemsProductValidation;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductManager
{
    protected $template;
    protected $check;

    public function __construct(Template $template, ItemsProductValidation $check)
    {
        $this->template = $template;
        $this->check = $check;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('status',1)->get();
        return $this->template->index($products);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //Add sizes
    public function store($request)
    {
        $response = [];

        $rules = $this->check->validation($request);

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $product = $request->only(['name','category_id', 'price','status','description','image']);
                if($request->hasFile('image')){
                   $product["image"] = $this->uploadImage($request->file('image'));
                }
                $data = Product::create($product);
                foreach ($request->sizes as $size) {
                    dump($size);
                    $data->sizes()->syncWithoutDetaching([$data->id =>[
                        'size' => $size
                    ]]);
                }

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
    public function show($product)
    {
        $response = $this->template->show($product);
        $response["comments"] = $product->comments;
        return $response;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $product)
    {
        $response = [];
        $rules = $this->check->validation($request);

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $item = $request->only(
                    [
                        'name',
                        'price',
                        'status',
                        'description',
                        'image'
                    ]
                );
                if($request->hasFile('image')){
                    $item["image"] = $this->uploadImage($request->file('image'));
                }

                foreach ($request->sizes as $sizes) {
                    dump($sizes);
                }

                $size = $request->only(
                    [
                        'size'
                        ,'unit_measure'
                    ]
                );
                $product->update($item);
                $product->sizes()->update($size);
                $response["message"] = "Successfully Updated ".$product->name;
                $response["data"] = $product;
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
    public function destroy(Product $product)
    {
        return $this->template->destroy($product);
    }


    /**
     * Validate the request provided.
     *
     * @param  int  $data
     * @return \Illuminate\Http\Response
     */
    public function uploadImage($request)
    {
        $path = public_path().'upload/images/store';
        $request->move($path,$request->getClientOriginalName());
        return $path;
    }
}





?>
