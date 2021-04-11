<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
class TaskController extends Controller
{
    public function index(){
       return Status::with('tasks')->get();
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => ['required', 'string'],
            'status_id' => ['required', 'exists:statuses,id']
        ]);
        return Task::create($request->only('content', 'status_id'));
    }
    public function update(Request $request, $id)
    {
        $task =  Task::find($id);
        $task->content = $request->content ;
        return $task->save();
    }
     public function sync(Request $request)
    {
        $this->validate(request(), [
            'columns' => ['required', 'array']
        ]);
        foreach ($request->columns as $status) {
            foreach ($status['tasks'] as $i => $task) {
                $order = $i + 1;
                if ($task['status_id'] !== $status['id'] || $task['order'] !== $order) {
                    Task::find($task['id'])->update(['status_id' => $status['id'], 'order' => $order]);
                }
            }
        }
        return Status::with('tasks')->get();
    } 

    public function destroy($id)
    {
        return Task::destroy($id);
    } 
}
