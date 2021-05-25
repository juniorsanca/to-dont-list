<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Carbon;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->id;

        $todos = Todo::with("user")
            ->where('user_id', $user_id)->latest()->get();
        return response()->json($todos, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create( Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string',
            'body' => 'required|string',
            'completed' => 'required|boolean'
        ]);


        $todo = Todo::create([
            'name' => $request->name,
            'body' => $request->body,
            'completed' => $request->completed,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($todo, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request,$id)
    {
        //
        $todo = Todo::find($id);
        if (empty($todo)){
            return response()->json("error : Impossible d'afficher cette tâche, cars elle n'existe pas!", 404);
        }
        if ($request->user()->id !== $todo->user_id) {
            return response()->json("error : cette tâche n'hesiste pas !", 403);
        }

        return response()->json($todo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required|string',
            'body' => 'required|string',
            'completed' => 'required|boolean'
        ]);

        $todo = Todo::find($id);
        if (empty($todo)){
            return response()->json("error : Cette tâche n'hesiste pas !", 404);
        }

        if ($request->user()->id !== $todo->user_id) {
            return response()->json("error : cette tâche n'hesiste pas !", 403);
        }

        $todo->update([
            'name' => $request->name,
            'body' => $request->body,
            'completed' => $request->completed,
        ]);

        $todoUpdated = Todo::find($id);
        return response()->json($todoUpdated, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        //
        $todo = Todo::find($id);
        if (empty($todo)){
            return response()->json("error : Impossible de supprimer cette tâche, cars elle n'existe pas!", 404);
        }
        if ($request->user()->id !== $todo->user_id) {
            return response()->json("error : cette tâche ne peut pas être supprimé", 403);
        }

        $todo->delete();
        return response()->json($todo, 200);
    }
}
