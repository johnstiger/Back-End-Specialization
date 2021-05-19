<?php

namespace App\Services\AllServices;

use App\Models\User;

class AllServices
{
    public function getUser($request)
    {
        return User::where('email',$request->email)->first();
    }

    public function allCustomers()
    {
        return User::where('is_admin',0)->get();
    }

    public function allAdmins()
    {
        return User::where('is_admin',1)->get();
    }


}



?>
