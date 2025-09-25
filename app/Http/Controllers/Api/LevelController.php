<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $levels = Level::all();
         return response()->json(["message" => "succès", "levels" => $levels], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' =>'required|string',
            ]);

            $result = Level::create($data);
        return response()->json(["message" => "succès", "result" => $result], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

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
                'name' =>'required|string',
            ]);

            $result = Level::find($id)->update($data);
        return response()->json(["message" => "succès", "result" => $result], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $level)
    {
       try {
            $result = Level::find($level)->delete();
            return response()->json(["message" => "succès", "reponse" => $result], 200);
        } catch (\Throwable $th) {
            return $th;
            return response()->json(["message" => "Error",], 400);
        }
    }
}
