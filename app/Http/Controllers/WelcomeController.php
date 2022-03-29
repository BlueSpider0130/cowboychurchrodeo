<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $organizations = Organization::inRandomOrder()->limit(4)->get();
        $totalOrganizations = $organizations->count() < 4 ? $organizations->count() : Organization::count();

        return view('welcome')
            ->with('organizations', $organizations)
            ->with('totalOrganizations', $totalOrganizations);
    }
}
