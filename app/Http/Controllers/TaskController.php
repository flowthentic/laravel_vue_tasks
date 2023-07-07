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
        $this->validateWrapper($request);

        $task = Task::create($request->all());
        $task['unsaved'] = false;

        return response()->json([
           'task'    => $task,
           'message' => 'Successfully created'
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
        $this->validateWrapper($request);

        $task->name = request('name');
        $task->description = request('description');
        $task->due = request('due');
        $task->completed = request('completed');

        $task->save();
        $task['unsaved'] = false;

        return response()->json([
           'task'    => $task,
           'message' => 'Successfully updated'
       ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    private function validateWrapper(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // validation of required fields
            'name' => 'required',
            'due' => 'required',
        ])->after(function ($validator) {
            // then validate if there aren't already two due dates of this value
            $data = $validator->getData();
            $matchingDueCount = Task::where('due', $data['due'])
                ->where('id', '<>', $data['id']) // ignore involved task
                ->count();
            if ($matchingDueCount > 1) {
                $validator->errors()
                ->add('due', 'Už existuje viacero úkolov s týmto dátumom.');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
