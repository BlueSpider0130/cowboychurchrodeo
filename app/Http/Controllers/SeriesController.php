<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Series;

class SeriesController extends Controller
{
    public function show( Organization $organization, Series $series )
    {
        $data['organization'] = $organization;
        $data['series'] = $series;
        return view('series.show', $data);
    }
}
