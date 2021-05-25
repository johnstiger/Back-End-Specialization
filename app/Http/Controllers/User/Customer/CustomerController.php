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
        return response()->json($this->manager->show($customer));
    }


    /*
    * Updating the Specific Customer's
    * Informations
    * return json type result
    */
    public function update(Request $request)
    {
        return response()->json($this->manager->update($request));
    }

   /*
    * Adding new Address of the Customer
    * return json type result
    */
    public function address(Request $request)
    {
        return response()->json($this->manager->address($request));
    }

}
