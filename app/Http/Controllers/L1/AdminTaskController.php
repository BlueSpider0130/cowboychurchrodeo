<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

use App\AdminTask;
use App\AdminTaskType;
use App\AdminTaskPriority;
use App\AdminTaskStatus;


class AdminTaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->displayIndex($request);
    }


    public function indexOpen(Request $request)
    {
        return $this->displayIndex($request, 'open');
    }


    public function indexClosed(Request $request)
    {
        return $this->displayIndex($request, 'closed');
    }


    /**
     * Display task list by filter
     */
    private function displayIndex( Request $request, string $filter = 'all' )
    {
        $query = AdminTask::with('created_by')
                                ->with('comments')
                                ->with('comments.user')
                                ->with('type')
                                ->with('priority')
                                ->with('status');

        if( 'open' == $filter )
        {
            $query->open();
        }

        if( 'closed' == $filter )
        {
            $query->closed();
        }

        $sort = $request->query('sort');

        if( $sort  &&  in_array($sort, ['date_asc', 'date_desc', 'status', 'type', 'priority']) )
        {
            if( 'date_asc' == $sort ) 
            {
                $query->orderBy('created_at', 'asc');
            }
            elseif( 'date_desc' == $sort ) 
            {
                $query->orderBy('created_at', 'desc');
            }
            else 
            {
                $query->orderBy( "{$sort}_id" );
            }
        }

        $tasks = $query->paginate(50);

        if( $sort )
        {
            $tasks->appends(['sort' => $sort]);
        }

        $data['tasks']  = $tasks;
        $data['filter'] = $filter;
        $data['sort']   = $sort;

        return view('L1/task_index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('L1/task_create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'     => [
                'required', 
                Rule::in(AdminTaskType::pluck('id')->toArray())
            ],
            'priority' => [
                'required',
                Rule::in(AdminTaskPriority::pluck('id')->toArray())
            ],
            'page'        => ['nullable', 'max: 255'],
            'description'     => ['required']
        ]);

        $task = AdminTask::create([
            'created_by_user_id' => $request->user()->id,
            'type_id' => $validated['type'],
            'priority_id' => $validated['priority'],
            'page' => $validated['page'],
            'description' => $validated['description']
        ]);

        return redirect()->route('admin.task.index.open')->with('successAlert', "Task #{$task->id} added");
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['task'] = AdminTask::findOrFail($id);

        return view('L1/task_show', $data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['task']       = AdminTask::findOrFail($id);
        $data['statuses']   = AdminTaskStatus::pluck('name', 'id')->toArray();
        $data['types']      = AdminTaskType::pluck('name', 'id')->toArray();
        $data['priorities'] = AdminTaskPriority::pluck('name', 'id')->toArray();

        return view('L1/task_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status_id' => 'nullable',
            'type_id' => 'required',
            'priority_id' => 'required',
            'page' => 'nullable',
            'description' => 'nullable'
        ]);

        $task = AdminTask::findOrFail($id);
        $task->update($validated);

        return redirect()->route('admin.task.show', $task->id)->with('successAlert', "Task #{$task->id} updated");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        AdminTask::destroy($id);
        
        return redirect()->route('admin.task.index');
    }


    public function closeTask(Request $request, $id)
    {
        $task = AdminTask::findOrFail($id);
        $task->closed = 1;
        $task->save();

        $redirect = route('admin.task.index');

        if( $request->query('r')  &&  Route::has( $request->query('r') ) )
        {
            $redirect = route( $request->query('r') );
        }

        return redirect($redirect)->with("successAlert", "Task #{$task->id} closed");
    }


    public function openTask(Request $request, $id)
    {
        $task = AdminTask::findOrFail($id);
        $task->closed = 0;
        $task->save();

        $redirect = route('admin.task.index');

        if( $request->query('r')  &&  Route::has( $request->query('r') ) )
        {
            $redirect = route( $request->query('r') );
        }

        return redirect($redirect)->with("successAlert", "Task #{$task->id} opened");
    } 

}
