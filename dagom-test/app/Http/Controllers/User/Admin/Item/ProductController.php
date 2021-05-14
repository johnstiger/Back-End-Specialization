<?php

namespace App\Http\Controllers\User\Admin\Item;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Admin\ServiceController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ServiceController $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('status',1)->with('sizes')->get();

        $response = $this->service->index($products);

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //Add sizes
    public function store(Request $request)
    {
        $response = [];

        $rules = $this->validation($request->all());

        try {
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                $product = $request->only(['name', 'price','status','description','image']);
                $size = $request->only(
                    [
                        'size',
                        'unit_measure',
                    ]
                );
                if($request->hasFile('image')){
                   $product["image"] = $this->uploadImage($request->file('image'));
                }
                $data = Product::create($product);
                $data->sizes()->create($size);
                $response["message"] = "Successfully Added ".$data->name." in Product!";
                $response["data"] = $data;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $response = $this->service->show($product);

        $response["comments"] = $product->comments;

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $response = [];

        $rules = $this->validation($request->all());

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
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $response = $this->service->destroy($product);

        return response()->json($response);
    }

    /**
     * Validate the request provided.
     *
     * @param  int  $data
     * @return \Illuminate\Http\Response
     */
    public function validation($request)
    {
        $rules = Validator::make($request,[
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'unit_measure' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'part' => 'required',
            'size' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ],[
            'category_id.required' => 'Category name field is required',
            'part.required' => 'This field is required, please enter valid value'
        ]);

        return $rules;
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
