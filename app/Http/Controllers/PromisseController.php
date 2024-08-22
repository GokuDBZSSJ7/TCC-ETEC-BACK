<?php

namespace App\Http\Controllers;

use App\Models\Promisse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromisseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $promisses = Promisse::all();
            return response()->json($promisses);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
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
            $validations = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'candidate_id' => 'required',
                'party_id' => 'required',
                'expected_time' => 'required'
            ]);

            if ($validations->fails()) {
                return response()->json("Erro de validação");
            }

            $promisse = Promisse::create([
                'title' => $request->title,
                'description' => $request->description,
                'candidate_id' => $request->candidate_id,
                'party_id' => $request->party_id,
                'expected_time' => $request->expected_time,
                'like' => $request->like,
                'deslike' => $request->deslike,
                'approvation' => 'Pendente'
            ]);

            return response()->json($promisse);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $promisse = Promisse::findOrFail($id);
            return response()->json($promisse);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
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
    public function update(Request $request, $id)
    {
        try {
            $promisse = Promisse::findOrFail($id);
            $promisse->update($request->all());

            return response()->json($promisse);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $promisse = Promisse::findOrFail($id);
            $promisse->delete();

            return response()->json("Deletado com sucesso!");
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }

    public function addLike($id)
    {
        try {
            $promisse = Promisse::findOrFail($id);
            $promisse->like += 1;

            return response()->json($promisse);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }

    public function removeLike($id)
    {
        try {
            $promisse = Promisse::findOrFail($id);
            $promisse->deslike += 1;

            return response()->json($promisse);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }
}
