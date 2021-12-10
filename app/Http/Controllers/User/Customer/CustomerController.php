<?php

namespace App\Http\Controllers\User\Customer;

use App\Http\Controllers\Controller;
use App\Managers\Users\Customers\CustomerManager;
use App\Models\Order;
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


    public function resetPassword(Request $request)
    {
        return response()->json($this->manager->resetPassword($request));
    }



    /*
    * Adding new Address of the Customer
    * return json type result
    */
    public function address(Request $request)
    {
        return response()->json($this->manager->address($request));
    }
    /**
     * Find all customer addresses
     *
     * @param mixed $customerId current user id
     */
    public function findAllCustomerAddress($customerId)
    {
        return response()->json($this->manager->findAllAddress($customerId));
    }

    public function findById($id)
    {
        return response()->json($this->manager->findAddressById($id));
    }

    public function updateAddress(Request $request, $id)
    {
        return response()->json($this->manager->updateAddress($request, $id));
    }

    /**
     * Orders resources
     * @param Request $request
     * @return Response $response
     */
    public function orders(Request $request) {
        return response()->json($this->manager->orders($request));
    }

    public function getOrderByUser(Request $request)
    {
        return response()->json($this->manager->ordersByUser($request));
    }


    public function showReceivedOrders()
    {
        return response()->json($this->manager->allReceivedOrders());
    }

    public function removeItem(Request $request, Order $order)
    {
        return response()->json($this->manager->removeItemOrder($request,$order));
    }
}
