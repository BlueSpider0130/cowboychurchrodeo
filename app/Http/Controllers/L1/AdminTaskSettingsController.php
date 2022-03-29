<?php

namespace App\Http\Controllers\L1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\AdminTaskType;
use App\AdminTaskPriority;
use App\AdminTaskStatus;


class AdminTaskSettingsController extends Controller
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
    
    public function index()
    {
        $data['types']      = AdminTaskType::all();
        $data['priorities'] = AdminTaskPriority::all();
        $data['statuses']   = AdminTaskStatus::all();

        return view('L1/task_settings_index', $data);
    }


    public function storeType(Request $request)
    {
        $names = AdminTaskType::pluck('name')->toArray();

        $validated = $request->validate([
            'type_name' => [
                'required',
                Rule::unique('admin_task_types', 'name')
            ]
        ]);
        
        AdminTaskType::create([
            'name' => $validated['type_name']
        ]);

        return redirect()->route('admin.task.settings.index')->with('successAlert', "Task type created");
    }


    public function deleteType(Request $request, $id)
    {
        $type = AdminTaskType::find($id);

        if( $type = AdminTaskType::find($id) )
        {
            $name = $type->name;
            $type->delete();

            $alert = [
                'type' => 'success',
                'message' => "Type '$name' deleted"
            ];
        }
        else
        {
            $alert = [
                'type' => 'danger',
                'message' => 'Type not found'
            ];
        }
        
        return redirect()->route('admin.task.settings.index')->with('alert', $alert);
    }



    public function storePriority(Request $request)
    {
        $names = AdminTaskPriority::pluck('name')->toArray();

        $validated = $request->validate([
            'priority_name' => [
                'required',
                Rule::unique('admin_task_priorities', 'name')
            ]
        ]);
        
        AdminTaskPriority::create([
            'name' => $validated['priority_name']
        ]);

        return redirect()->route('admin.task.settings.index')->with('successAlert', "Task priority type created");
    }


    public function deletePriority(Request $request, $id)
    {
        $priority = AdminTaskPriority::find($id);

        if( $priority = AdminTaskPriority::find($id) )
        {
            $name = $priority->name;
            $priority->delete();

            $alert = [
                'type' => 'success',
                'message' => "Priority '$name' deleted"
            ];
        }
        else
        {
            $alert = [
                'type' => 'danger',
                'message' => 'Priority not found'
            ];
        }
        
        return redirect()->route('admin.task.settings.index')->with('alert', $alert);
    }



    public function storeStatus(Request $request)
    {
        $names = AdminTaskStatus::pluck('name')->toArray();

        $validated = $request->validate([
            'status_name' => [
                'required',
                Rule::unique('admin_task_statuses', 'name')
            ]
        ]);
        
        AdminTaskStatus::create([
            'name' => $validated['status_name']
        ]);

        return redirect()->route('admin.task.settings.index')->with('successAlert', 'Task status type created');
    }


    public function deleteStatus(Request $request, $id)
    {
        $status = AdminTaskStatus::find($id);

        if( $status = AdminTaskStatus::find($id) )
        {
            $name = $status->name;
            $status->delete();

            $alert =[
                'type' => 'success',
                'message' => "Status '$name' deleted"
            ];
        }
        else
        {
            $alert = [
                'type' => 'danger',
                'message' => 'Status not found'
            ];
        }
        
        return redirect()->route('admin.task.settings.index')->with('alert', $alert);
    }
}
