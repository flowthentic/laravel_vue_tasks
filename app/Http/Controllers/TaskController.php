<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return response()->json([
           'tasks'    => $tasks,
       ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // implemented in vue
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $task = $request->all();
        $task['errors'] = $this->reportErrors($request);

        if (count($task['errors']) == 0)
            $task = Task::create($task);
        else $task['changed'] = true;

        return response()->json([
           'task'    => $task,
       ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $task->errors = $this->reportErrors($request);

        $task->name = request('name');
        $task->description = request('description');
        $task->due = request('due');
        $task->completed = request('completed');

        if (count($task->errors) == 0)
            $task->save();
        else $task->changed = true;

        return response()->json([
           'task'    => $task,
       ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    private function reportErrors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // validation of required fields
            'name' => 'required',
            'due' => 'required',
        ],[
            'name.required' => 'Názov úkolu je vyžadovaný',
            'due.required' => 'Termín úlohy je povinný',
        ])->after(function ($validator) {
            // then validate if there aren't already two due dates of this value
            $data = $validator->getData();
            $matchingDueCount = Task::where('due', $data['due'])
                ->where('id', '<>', $data['id']) // ignore involved task
                ->count();
            $validator->errors()->addif($matchingDueCount > 1,
                'due', 'Už existuje viacero úkolov s týmto dátumom.');
        });

        $messages = $validator->errors()->messages();
        //extract strings from single element arrays
        array_walk($messages, fn(&$message) => $message = $message[0]);
        return $messages;
    }
}
