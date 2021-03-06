<?php

namespace App\Managers\Orders;

use App\Managers\Template\Template;
use App\Services\Data\DataServices;
use App\Services\Mail\SendEmailServices;
use App\Validations\Orders\OrderValidation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderManager
{

    protected $check;
    protected $template;
    protected $service;
    protected $email;
    public function __construct(OrderValidation $check, Template $template, DataServices $service, SendEmailServices $email)
    {
        $this->check = $check;
        $this->template = $template;
        $this->service = $service;
        $this->email = $email;
    }

    public function index()
    {
        return $this->template->index($this->service->Orders());
    }

    public function pendingOrders()
    {
        return $this->template->index($this->service->pendingOrders());
    }

    public function notification()
    {
        return $this->template->index($this->service->getPendingNotification());
    }

    public function updatePendingOrder()
    {
        return $this->template->show($this->service->updateViewPending());
    }

    public function orderConfirmed($request, $customer)
    {
        try {
            $response = [];
            $order = $customer->orders->where('id', $request[0]['id'])->first();
            if (!$order) {
                $response["message"] = "There is no order to update";
                $response["error"] = true;
            } else {
                $order->update(['status' => config('const.order.confirmed'), 'total' => $request[0]['total']]);
                $order->created_at = Carbon::now();
                $order->save();
                // foreach ($order->products as $product) {
                //     foreach ($product->sizes as $size) {
                //         $available = $size['pivot']["avail_unit_measure"] > $product['pivot']['quantity'] ? $size['pivot']["avail_unit_measure"] - $product['pivot']['quantity'] : 0;
                //         $product->sizes()->syncWithoutDetaching([
                //             $size["id"] => [
                //                 'avail_unit_measure' => $available,
                //             ]
                //         ]);
                //     }
                // }
                $response["message"] = "Order Confirmed";
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error " . $error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    public function declinedOrder($request, $customer)
    {
        try {
            $response = [];
            $order = $customer->orders->where('id', $request[0]['id'])->first();
            if (!$order) {
                $response["message"] = "There is no order to update";
                $response["error"] = true;
            } else {
                foreach ($order->products as $product) {
                    foreach ($product->sizes as $size) {
                        $available = $size['pivot']["avail_unit_measure"] + $product['pivot']['quantity'];
                        $product->sizes()->syncWithoutDetaching([
                            $size["id"] => [
                                'avail_unit_measure' => $available,
                            ]
                        ]);
                    }
                    $product->update([
                        'status' => 1
                    ]);
                }
                $order->update(['status' => config('const.order.declined'), 'total' => $request[0]['total']]);
                $response["message"] = "Order Declined";
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error " . $error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    public function create()
    {
        $user = Auth::user();
        $response = [];
        try {
            $user->orders()->create();
            $response["message"] = "Order is now Ready";
            $response["data"] = $user->orders;
        } catch (\Exception $error) {
            $response["message"] = "Error " . $error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    public function store($request)
    {
        $response = [];
        $total = 0;
        $rules = $this->check->validation($request);
        $user = Auth::user();

        $name_of_receiver = env('NAME_OF_RECEIVER', 'Daryl Criz Angel Rayco');
        $contact_number = env('CONTACT_NUMBER','09856421355');
        $gCash_account = env('G_CASH_ACCOUNT','09856421355');

        try {
            if ($rules->fails()) {
                $response["message"] = $rules->errors();
                $response["error"] = true;
            } else {

                if ($user->orders->isEmpty()) {
                    $this->create($user);
                }

                foreach ($request->data as $data) {
                    $user->orders->last()->products()->syncWithoutDetaching([
                        $data["product_id"] => [
                            'quantity' => $data["quantity"],
                            'subtotal' => $data["subtotal"],
                            'size_id' => $data['size_id']
                        ]
                    ]);
                    $total += $data["subtotal"];
                    $user->cart->products()->detach($data["product_id"]);
                }

                $user->orders->last()->update([
                    'total' => $total,
                    'payment_method' => $request->payment_method,
                    'address_id' => $request->address_id
                ]);

                if($request->data[0]["checkout"]){
                    $product = $user->orders->last()->products->where('id',$request->data[0]["product_id"])->first();
                    $size = $product->sizes->where('id',$request->data[0]["size_id"])->first();
                    $product->sizes()->syncWithoutDetaching([
                        $request->data[0]["size_id"] => [
                            'avail_unit_measure' => $size->pivot->avail_unit_measure - $request->data[0]["quantity"],
                            ]
                    ]);
                }

                $number = '';
                $mode = '';
                $through = '';

                if($request->payment_method == "palawan"){
                    $number = $contact_number;
                    $mode = "Contact Number";
                    $through = "Palawan Express";
                }else{
                    $number = $gCash_account;
                    $mode = "GCash Account";
                    $through = "GCash";
                }

                $this->email->sendPaymentDetails($user, $name_of_receiver, $number, $mode, $through);
                $response["message"] = "Success";
                $response["data"] = $user->orders->last()->products;
                $response["order"] = $user->orders->last();
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error " . $error->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

    public function show()
    {
        $user = Auth::user();
        if ($user->orders->isEmpty()) {
            $response = $this->template->NoData();
        } else {
            $response = $this->template->show($user->orders()->with('products')->get());
        }

        return $response;
    }


    public function addTrackingCode($request, $customer)
    {
        $rules = $this->check->trackingValidation($request);
        $response = [];

        if ($rules->fails()) {
            $response['message'] = $rules->errors();
            $response['error'] = true;
        } else {
            $order = $customer->orders->where('id', $request['order_id'])->first();
            $order->update(['tracking_code' => $request['tracking_code'],'total'=>$request["shipping_fee"]+$order->total]);
            $order->delivery()->create([
                'delivery_date' => Carbon::now()->addDays(7),
                'name_of_deliver_company' => $request['name_of_deliver_company'],
            ]);
            $response['message'] = 'Successfully Added Tracking Code';
            $response['error'] = false;
        }

        return $response;
    }


    public function received($order)
    {
        $response = [];
        $order->update(['status' => 3]);
        $order->delivery()->update(['delivery_recieve_date' => Carbon::now()]);
        $response["message"] = "Successfully Received Order";
        $response["error"] = false;
        return $response;
    }
}
