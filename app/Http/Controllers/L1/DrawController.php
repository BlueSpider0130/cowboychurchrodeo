<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $data['rodeos'] = \App\Rodeo::orderBy('starts_at')->get();
        return view('L1.draw.index', $data);
    }

    public function clear(\App\Rodeo $rodeo)
    {
        $ids = $rodeo->competition_entries()->pluck('competition_entries.id')->toArray();
        \App\CompetitionEntry::whereIn('id', $ids)->update(['draw' => null]);

        return redirect()
            ->route('admin.draw.index')
            ->with('successAlert', "Rodeo \"{$rodeo->name}\" draw cleared.");
    }
}
