<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::all();
            return view("index", ['tasks' => $tasks]);
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function storeTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string | required | max : 200 | regex:/^[^<>]*$/',
        ], [
            'title.regex' => 'Task must not contain any html'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Task::create([
                'title' => $request->title,
            ]);
            return redirect('/tasks')->with('success', 'Task added successfully');
        } catch (\Exception $e) {
            report($e);
            return redirect('tasks')->with('error', $e->getMessage());
        }

    }

    public function deleteTask($id){
        try{
            $task = Task::find($id);
            if(! $task){
                return redirect('tasks')->with('error','Task does not exist');
            }
            $task->delete();
            return redirect('tasks')->with('success','Task deleted successfully');
        }catch (\Exception $e) {
            report($e);
            return redirect('tasks')->with('error', 'Faied to delete task '.$e->getMessage());
        }
    }

    public function editTask(Request $request){
        try{

            $taskId = $request->input('taskId');
            $taskTitle = $request->input('taskTitle');
            // \Log::info(''.$taskId);
            // \Log::info(''.$taskTitle);
            try{
                $task = Task::find($taskId);
                if(!$task){
                    return redirect('tasks')->with('error','Task not found');
                }
            }catch(\Exception $e) {
                report($e);
            }

            $validator = Validator::make($request->all(), [
                'taskTitle' => 'string | required | max : 200 | regex:/^[^<>]*$/',
            ], [
                'taskTitle.regex' => 'Task must not contain any html'
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            try{
                $task->fill([
                    'title'=> $taskTitle
                ]);
                $task->save();
                return redirect('tasks')->with('success','Task Updated Successfully');
            }catch (\Exception $e) {
                report($e);
                return redirect('task')->with('error','Failed to update task '.$e->getMessage()) ;
            }
            
        }catch (\Exception $e) {
            report($e);
            return redirect('tasks')->with('error', $e->getMessage());
        }
    }
}
