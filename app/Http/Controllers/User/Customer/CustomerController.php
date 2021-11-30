<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CustomerManager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function showAll()
    {
        // return response()->json($this->manager->show($customer));
        return User::all();
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
