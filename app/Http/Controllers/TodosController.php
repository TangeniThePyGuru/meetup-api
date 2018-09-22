<?php

namespace App\Http\Controllers;

use App\Todo;
use App\User;
use Illuminate\Http\Request;

class TodosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todo = Todo::first();
        return response()->json($todo->with('owner')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = Todo::create([
            'task' => $request->task,
            'completed' => $request->completed,
            'user_id' => User::all()->random(1)->pluck('id')[0]
        ]);

        return response()->json(['message' => 'Todo successfully created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return response()->json($todo->with('owner')->get()->where('id', '=', $todo->id), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json(['message' => "Todo with id {$todo->id} has been successfully deleted"]);
    }
}
