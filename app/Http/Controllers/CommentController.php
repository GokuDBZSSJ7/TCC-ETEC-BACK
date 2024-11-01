<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            return response()->json($comments, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error retrieving comments', 'error' => $e->getMessage()], 500);
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
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'proposal_id' => 'required|exists:proposals,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $comment = Comment::create($request->all());

            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating comment', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            return response()->json($comment, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Comment not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error retrieving comment', 'error' => $e->getMessage()], 500);
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
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'proposal_id' => 'required|exists:proposals,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $comment = Comment::findOrFail($id);
            $comment->update($request->all());

            return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Comment not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating comment', 'error' => $e->getMessage()], 500);
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

            return response()->json(['message' => 'Deletado com sucesso!'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Comment not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting comment', 'error' => $e->getMessage()], 500);
        }
    }
}
