<?php

namespace App\Http\Controllers\User\Admin;

use App\Http\Controllers\Controller;
use App\Managers\Users\Admin\DashboardManager;
class DashboardController extends Controller
{

    private $manager;
    public function __construct(DashboardManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->manager->index());
    }



}
