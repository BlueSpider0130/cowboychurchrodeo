<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\AdminTask;
use App\AdminTaskComment;

class AdminTaskCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => [
                'required',
            ],
            'body' => [
                'required'
            ]
        ]);

        $validated['user_id'] = $request->user()->id;

        AdminTaskComment::create($validated);

        return redirect()->route('admin.task.show', $validated['task_id'])->with('successAlert', 'Comment added');
    }
}
