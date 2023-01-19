<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\TODO;

class TODOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return auth()->user()->todos()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTodoRequest $request)
    {
        $request->user()->todos()->create($request->all());

        return response()->json([
            "message" => "Todo is added successfully."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TODO  $tODO
     * @return \Illuminate\Http\Response
     */
    public function show($todo)
    {
        $todo = TODO::where('id', $todo)->first(['title', 'description']);
        return response()->json($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TODO  $tODO
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTodoRequest $request, TODO $todo)
    {
        $todo->update($request->all());

        return response()->json([
            "message" => "Todo is updated successfully."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TODO  $tODO
     * @return \Illuminate\Http\Response
     */
    public function destroy(TODO $todo)
    {
        $todo->delete();

        return response()->json([
            "message" => "Todo is deleted successfully."
        ]);
    }

    public function filter($title)
    {
        return request()->user()->todos()->where('title', 'LIKE', "%$title%")->first();
    }
}
