<?php

namespace App\Managers\Items;

use App\Managers\Template\Template;
use App\Validations\Items\ProductValidation as ItemsProductValidation;
use App\Models\Product;
use App\Services\Data\DataServices;

class ProductManager
{
    protected $template;
    protected $check;
    protected $services;

    public function __construct(Template $template, ItemsProductValidation $check, DataServices $services)
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
        $products = Product::with(['category','sizes'])->get();
        foreach($products as $product){
            $units = 0;
            if(!empty($product['sizes'])){
                foreach ($product['sizes'] as $size) {
                    if($size['pivot']['avail_unit_measure'] > 0){
                        $units += $size['pivot']['avail_unit_measure'];
                    }
                }
            }
            if($units == 0){
                $product->status = 0;
                $product->save();
            }
        }
        $availableProducts = Product::where('status',config('const.product.available'))->with(['category','sizes'])->get();
        return $this->template->index($availableProducts);
    }

    public function getSizes()
    {
        return $this->template->index($this->services->getSizes());
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

                $product = $request->only(['name','category_id', 'price','status','description']);
                $product['image'] = $request['fileSource'];
                $newProduct = Product::create($product);
                foreach($request->sizes as $data){
                    $newProduct->sizes()->syncWithoutDetaching([
                        $data["size_id"] => [
                            'unit_measure' => $data["unit_measure"],
                            'avail_unit_measure' => $data["unit_measure"]
                        ]
                    ]);
                }
                    // $newProduct->sizes()->syncWithoutDetaching([
                    //     $request->sizes => [
                    //         'unit_measure' => $request["unit_measure"],
                    //         'avail_unit_measure' => $request["unit_measure"]
                    //     ]
                    // ]);

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

    public function putImage($request, $product)
    {
        $rules = $this->check->imageValidation($request);

        $response = [];

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                if($request->hasFile('image')){
                    $data["image"] = $this->uploadImage($request->file('image'));
                    $product->update($data);
                    $response['message'] = "Successfully Updated Image";
                    $response["error"] = false;
                }else{
                    $response["message"] = "No Image Found!";
                    $response["error"] = true;
                }
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
                $item['image'] = $request['fileSource'];

                // if($request->hasFile('image')){
                //     $item["image"] = $this->uploadImage($request->file('image'));
                // }

                $product->update($item);

                // $product->sizes()->syncWithoutDetaching([
                //     $request->sizes => [
                //         'unit_measure' => $request["unit_measure"],
                //         'avail_unit_measure' => $request["unit_measure"]
                //     ]
                // ]);

                if(!empty($request['deletedSizes'])){
                    foreach($request['deletedSizes'] as $item){
                        $product->sizes()->where('id',$item['size_id'])->wherePivot('sizes_id',$item['size_id'])->detach();
                    }
                }

                foreach($request->sizes as $data){
                    $status = $data['pivot']['avail_unit_measure'] > 0 ? true : false;
                    $product->sizes()->syncWithoutDetaching([
                        $data['pivot']["sizes_id"] => [
                            'status' => $status,
                            'unit_measure' => $data['pivot']["avail_unit_measure"],
                            'avail_unit_measure' => $data['pivot']["avail_unit_measure"]
                        ]
                    ]);
                }

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
        $picture = time().'-'.rand(1000,9999).'.'.$extension;
        $request->move(public_path('img'), $picture);

        return $picture;
        // $path = public_path().'upload/images/store';
        // $request->move($path,$request->getClientOriginalName());
        // return $path;
    }

}





?>
