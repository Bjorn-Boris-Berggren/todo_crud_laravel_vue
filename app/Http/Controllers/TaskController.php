<?php

namespace App\Http\Controllers;

/**
 * The controller that manages the tasks.
 *
 * @author Björn Berggren
 */

use App\Model\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{

    public function __construct()
    {}

    /**
     * Display a listing of the task.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Task::all()->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Store a newly created task in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validates user-input
        $request->validate(
            [
                'title' => 'required',
            ]
        );
        $task = new Task();
        $task->title = $request->title;
        $task->done = false;
        $task->save();
        return response($task->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Remove specified task from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        // Sends 404 if the task is not found
        Task::findOrFail($id);
        Task::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
    /**
     * Will edit the task.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validates user-input
        $request->validate(
            [
                'id' => 'required|integer',
                'title' => 'required',
                'done' => 'required|boolean',
            ]
        );

        // Sends 404 if the task is not found
        $task = Task::findOrFail($request->id);
        $task->title = $request->title;
        $task->done = $request->done;
        $task->save();
        return response($task->jsonSerialize(), Response::HTTP_OK);
    }
}
