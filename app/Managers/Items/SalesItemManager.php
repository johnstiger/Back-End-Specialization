<?php

namespace App\Managers\Items;

use App\Managers\Template\Template;
use App\Models\Sizes;
use App\Services\Data\DataServices;
use App\Validations\Items\ProductValidation;

class SalesItemManager
{
    private $data;
    private $template;
    private $validator;

    public function __construct(DataServices $data, Template $template, ProductValidation $validator){
       $this->data = $data;
       $this->template = $template;
       $this->validator = $validator;
    }


    public function index()
    {
        return $this->template->index($this->data->salesItem());
    }

    public function store($request ,$product)
    {
        $params = $request->all();
        $response = [];
        try {
            // $rules = $this->validator->salesItemValidation($params);
            $rules = $this->validator->specificValidation($params);
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                if($product){
                    if($params['promo_type'] == '%'){
                        $percent = ($params['percent_off']/100)*$product->price;
                    }else{
                        $percent = $params['percent_off'];
                    }
                    $price = ($product->price - $percent);
                    $product->update([
                        'description' => $params['description'],
                        'promo_price' => $params['percent_off'],
                        'promo_type' => $params['promo_type'],
                        'is_sale' => true,
                        'sale_price' => $price
                    ]);
                  $response["message"] = "Successfully Added to Sales Item";
                  $response["data"] = $product;
                  $response["error"] = false;
                }else{
                    $response["message"] = "Product is not Found";
                    $response["error"] = true;
                }
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }


    public function show($data)
    {
        return $this->template->show($this->data->getSalesItem($data));
    }

    public function update($request, $salesItem)
    {
        $response = [];
        try {
            // $rules = $this->validator->salesItemValidation($request->all());
            $rules = $this->validator->updateSaleValidation($request->all());
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                if($salesItem){
                    // $size = $salesItem->products->sizes->first();
                    // if($salesItem->unit_measure > $request['unit_measure']){
                    //     $salesItem->products->sizes()->syncWithoutDetaching([
                    //         $size->id=>[
                    //             'avail_unit_measure' => $size->avail_unit_measure + $request['unit_measure']
                    //         ]
                    //     ]);
                    // }else if ($salesItem->unit_measure < $request['unit_measure']){
                    //     $salesItem->products->sizes()->syncWithoutDetaching([
                    //         $size->id=>[
                    //             'avail_unit_measure' => $size->avail_unit_measure - $request['unit_measure']
                    //         ]
                    //     ]);
                    // }
                    $params = $request->all();
                    if($params['promo_type'] == '%'){
                        $percent = ($params['percent_off']/100)*$salesItem->price;
                    }else{
                        $percent = $params['percent_off'];
                    }
                    $price = (int)($salesItem->price - $percent);

                   $salesItem->update([
                    'description' => $params['description'],
                    'promo_price' => $params['percent_off'],
                    'promo_type' => $params['promo_type'],
                    'sale_price' => $price
                   ]);

                   $response["message"] = "Successfully updated";
                   $response["error"] = false;
                }else{
                    $response["message"] = "No data found";
                    $response["error"] = true;
                }
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }


    public function delete($salesItem)
    {
        $response = [];
        if($salesItem){
            $salesItem->update([
                'is_sale' => false
            ]);
            $response["message"] = "Successfully deleted sale item, it will return the product in products page";
            $response["error"] = false;
        }else{
            $response["error"] = true;
            $response["message"] = "No data found!";
        }

        return $response;
        // $size =  $salesItem->products->sizes->first();
        // $salesItem->products->sizes()->syncWithoutDetaching([
        // $size->id=>[
        //     'avail_unit_measure' => $size->pivot->avail_unit_measure + $salesItem->unit_measure
        // ]
        // ]);
        // return $this->template->index($salesItem);
    }


    // Back Up Store Sale by sizes
    // if(count($params['size']) > 0){
    //     foreach ($params['size'] as $item) {
    //         $total = $price*$item['pivot']['avail_unit_measure'];
    //         $status = $item['pivot']['avail_unit_measure'] > 0 ? true : false;
    //         $product->sizes()->syncWithoutDetaching([
    //             $item["id"] => [
    //                 'status' => $status,
    //                 'avail_unit_measure' => $item['pivot']['avail_unit_measure']
    //             ]
    //         ]);
    //         $product->salesItem()->create([
    //             'description' => $params['description'],
    //             'percent_off' => $params['percent_off'],
    //             'promo_type'  => $params['promo_type'],
    //             'unit_measure'=> $item['pivot']["sales_item"],
    //             'size' => $item['size'],
    //             'price' => $price,
    //             'total' => $total
    //         ]);

    //     }
    // }else{
    //     $total = $price*$params['unit_measure'];
    //     $size = $params['size'] == 0 ? "" : Sizes::where('id',$params['size'])->first()->size;
    //     $product->salesItem()->create([
    //         'description' => $params['description'],
    //         'percent_off' => $params['percent_off'],
    //         'promo_type'  => $params['promo_type'],
    //         'unit_measure'=> $params['unit_measure'],
    //         'size' => $size,
    //         'price' => $price,
    //         'total' => $total
    //     ]);
    //     $availUnit = $product->sizes()->first()->pivot->avail_unit_measure;
    //     $status = $availUnit > 0 ? true : false;
    //     $product->sizes()->syncWithoutDetaching([
    //           $params["size"] => [
    //               'status' => $status,
    //               'avail_unit_measure' => $availUnit - $params["unit_measure"]
    //           ]
    //       ]);
    // }



}


?>
