<?php

namespace App\Managers\Items;

use App\Managers\Template\Template;
use App\Models\Category;
use App\Validations\Items\ProductValidation as ItemsProductValidation;
use App\Models\Product;

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
        // return $this->template->index(Category::with('products')->get());
        return $this->template->index(Product::with(['category','sizes'])->get());
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
                // $product = $request->only(['name','category_id','part', 'price','status','description','image']);
                if($request->hasFile('image')){
                   $product["image"] = $this->uploadImage($request->file('image'));
                }
                $newProduct = Product::create($product);
                    $newProduct->sizes()->syncWithoutDetaching([
                        $request->sizes => [
                            'unit_measure' => $request["unit_measure"],
                            'avail_unit_measure' => $request["unit_measure"]
                        ]
                    ]);

                $response["message"] = "Successfully Added ".$newProduct->name." in Product!";
                $response["data"] = $newProduct;
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
        $response["sizes"] = $product->sizes;
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
                        'part',
                        'status',
                        'description',
                        'image',
                        'category_id'
                    ]
                );
                if($request->hasFile('image')){
                    $item["image"] = $this->uploadImage($request->file('image'));
                }
                $product->update($item);
                $product->sizes()->syncWithoutDetaching([
                    $request->sizes => [
                        'unit_measure' => $request["unit_measure"],
                        'avail_unit_measure' => $request["unit_measure"]
                    ]
                ]);
                // foreach($request->sizes as $data){
                //     $product->sizes()->syncWithoutDetaching([
                //         $data["size_id"] => [
                //             'unit_measure' => $data["unit_measure"],
                //             'avail_unit_measure' => $data["unit_measure"]
                //         ]
                //     ]);
                // }
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
        $filename = $request->getClientOriginalName();
        $extension = $request->getClientOriginalExtension();
        $picture = date('His').'-'.$filename;
        $request->move(public_path('img'), $picture);

        return $picture;
        // $path = public_path().'upload/images/store';
        // $request->move($path,$request->getClientOriginalName());
        // return $path;
    }
}





?>
