<?php

namespace App\Http\Controllers\User\Admin;

use App\Http\Controllers\Controller;
use App\Managers\Users\Admin\AdminManager as AdminAdminManager;
use App\Models\Users\User;
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
        return response()->json($this->manager->customers());

    }

    /*
    * Displaying All Admins
    * return json type result
    */
    public function index()
    {
        return response()->json($this->manager->admins());
    }

    /*
    * Adding new Admin User
    * return json type result
    */
    public function store(Request $request)
    {
        return response()->json($this->manager->store($request));
    }

    /*
    * Showing Specific Admin
    * return json type result
    */
    public function show(User $admin)
    {
        return response()->json($this->manager->show($admin));
    }

    /*
    * Updating Specific Admin's Informations
    * return json type result
    */
    public function update(Request $request, User $admin)
    {
        return response()->json($this->manager->update($request, $admin));
    }

    /*
    * Removing the Specific Admin with SoftDelete
    * return json type result
    */
    public function destroy(User $admin)
    {
        return response()->json($this->manager->destroy($admin));
    }


}
