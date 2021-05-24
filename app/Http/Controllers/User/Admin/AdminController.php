<?php

namespace App\Http\Controllers\User\Admin;

use App\Http\Controllers\Controller;
use App\Managers\Users\Admin\AdminManager as AdminAdminManager;
use App\Models\Items\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $manager;

    public function __construct(AdminAdminManager $manager)
    {
        $this->manager = $manager;
    }

    /*
    * Displaying All Customers
    * return json type result
    */
    public function customers()
    {
        $response = $this->manager->customers();
        return response()->json($response);

    }

    /*
    * Displaying All Admins
    * return json type result
    */
    public function index()
    {
        $response = $this->manager->admins();
        return response()->json($response);
    }

    /*
    * Adding new Admin User
    * return json type result
    */
    public function store(Request $request)
    {
        $response = $this->manager->store($request);
        return response()->json($response);
    }

    /*
    * Showing Specific Admin
    * return json type result
    */
    public function show(User $admin)
    {
        $response = $this->manager->show($admin);
        return response()->json($response);
    }

    /*
    * Updating Specific Admin's Informations
    * return json type result
    */
    public function update(Request $request, User $admin)
    {
        $response = $this->manager->update($request, $admin);
        return response()->json($response);
    }

    /*
    * Removing the Specific Admin with SoftDelete
    * return json type result
    */
    public function destroy(User $admin)
    {
        $response = $this->manager->destroy($admin);
        return response()->json($response);
    }


}
