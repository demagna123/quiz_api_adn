<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $questions = Question::all();
            return response()->json(["message" => "succès", "questions" => $questions], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                "level_theme_id" => "required|exists:level_themes,id",
                "description" => "required|string|min:4",
                "marke" => "required|numeric",
            ]);

            $response = Question::create($request->all());
            return response()->json(["message" => "succès", "reponse" => $response], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                "level_theme_id" => "required|exists:level_themes,id",
                "description" => "required|string|min:4",
                "marke" => "required|numeric",

            ]);

            $response = Question::find($id)->update($request->all());
            return response()->json(["message" => "succès", "reponse" => $response], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = Question::find($id)->delete();
            return response()->json(["message" => "succès", "reponse" => $result], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }
}
