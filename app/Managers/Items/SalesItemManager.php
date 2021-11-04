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
            // $product = $this->data->getProductToSales($params->id);
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
                    $total = ($product->price - $percent)*$params['unit_measure'];
                    $size = Sizes::where('id',$params['size'])->first();
                  $product->salesItem()->create([
                      'description' => $params['description'],
                      'percent_off' => $params['percent_off'],
                      'promo_type'  => $params['promo_type'],
                      'unit_measure'=> $params['unit_measure'],
                      'size' => $size->size,
                      'total' => $total
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
            $response["message"] = "Error ".$error;
            $response["error"] = true;
        }

        return $response;
    }



}


?>
