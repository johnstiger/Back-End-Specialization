<?php

namespace App\Services\Data;

use App\Models\User;

class DataServices
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

    public function getAdmin($request)
    {
        return User::where('is_admin',1)->where('email',$request->email)->first();
    }

}



?>
