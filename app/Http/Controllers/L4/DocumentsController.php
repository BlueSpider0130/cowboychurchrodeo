<?php

namespace App\Http\Controllers\L4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;

class DocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function series(Organization $organization)
    {
        return $organization->series()->inProgress()->get();
    }
}
