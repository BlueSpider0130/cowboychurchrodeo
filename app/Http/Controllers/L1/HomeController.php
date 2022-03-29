<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use App\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $data['organizationCount'] = Organization::count();
        $data['userCount'] = User::count();

        return view('L1.home', $data);
    }
}
