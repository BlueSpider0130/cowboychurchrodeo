<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdminTask;

class FeedbackController extends Controller
{
    /**
     * Store feedback as an admin task
     */
    public function storeFeedback(Request $request)
    {
        if( null !== $request->input('page') && null !== $request->input('comment') )
        {
            AdminTask::create([                
                'created_by_user_id' => $request->user() ? $request->user()->id : null,
                'page' => $request->input('page'),
                'description' => $request->input('comment')
            ]);
        }
    }

}
