<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($proposalId)
    {
        try {
            $comments = Comment::where('proposal_id', $proposalId)->get();
            return $comments;
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
            $validate = Validator::make($request->all(), [
                'user_id' => 'required',
                'proposal_id' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 422);
            }

            $comment = Comment::create($request->all());

            return $comment;
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            return $comment;
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
            $comment = Comment::findOrFail($id);
            $comment->update($request->all());

            return $comment;
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
            $comment = Comment::findOrFail($id);
            $comment->delete();
            return response()->json("Deletado com sucesso!");;
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }
}
