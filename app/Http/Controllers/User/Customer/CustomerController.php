<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CustomerManager;
use App\Models\Users\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $manager;

    public function __construct(CustomerManager $manager)
    {
        $this->manager = $manager;
    }

    /*
    * Showing Specific Customer
    * return json type result
    */
    public function show(User $customer)
    {
        $response = $this->manager->show($customer);
        return response()->json($response);
    }


    /*
    * Updating the Specific Customer's
    * Informations
    * return json type result
    */
    public function update(Request $request)
    {
        $response = $this->manager->update($request);
        return response()->json($response);
    }

   /*
    * Adding new Address of the Customer
    * return json type result
    */
    public function address(Request $request)
    {
        $response = $this->manager->address($request);
        return response()->json($response);
    }

}
