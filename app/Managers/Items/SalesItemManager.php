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
            $rules = $this->validator->salesItemValidation($params);
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
                    $total = $price*$params['unit_measure'];
                    $size = $params['size'] == 0 ? "" : Sizes::where('id',$params['size'])->first()->size;
                  $product->salesItem()->create([
                      'description' => $params['description'],
                      'percent_off' => $params['percent_off'],
                      'promo_type'  => $params['promo_type'],
                      'unit_measure'=> $params['unit_measure'],
                      'size' => $size,
                      'price' => $price,
                      'total' => $total
                  ]);
                  $availUnit = $product->sizes()->first()->pivot->avail_unit_measure;
                  $product->sizes()->syncWithoutDetaching([
                        $params["size"] => [
                            'avail_unit_measure' => $availUnit - $params["unit_measure"]
                        ]
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
            $rules = $this->validator->salesItemValidation($request->all());
            if($rules->fails()){
                $response["message"] = $rules->errors();
                $response["error"] = true;
            }else{
                if($salesItem){
                   $salesItem->update($request->all());
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
        return $this->template->destroy($salesItem);
    }



}


?>
