<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Promisse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                'political_id' => 'required',
                'party_id' => 'required',
                'time' => 'required',
                'area_id' => 'required',
                'budget' => 'required',
                'status' => 'required'
            ]);

            if ($validations->fails()) {
                return response()->json($validations->errors(), 422);
            }

            $imagePath = $this->handleImageUpload($request->image_url);

            $data = $request->all();
            if ($imagePath) {
                $data['image_url'] = $imagePath;
            }

            $promisse = Promisse::create($data);

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

    protected function handleImageUpload($imageData)
    {
        if ($imageData && !empty($imageData)) {
            $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
            $imageName = time() . '.jpg';
            $imagePath = 'images/promisses/' . $imageName;
            Storage::disk('public')->put($imagePath, base64_decode($base64Image));

            return $imagePath;
        }
        return null;
    }

    public function getFinishedProposals($id) {
        $proposals = Promisse::where('political_id', $id)->get();
        $finishes = $proposals->filter(function ($proposal) {
            return $proposal->status == 'Finalizada';
        });
    
        return $finishes;
    }
    
    public function getWorkingProposals($id) {
        $proposals = Promisse::where('political_id', $id)->get();
        $finishes = $proposals->filter(function ($proposal) {
            return $proposal->status == 'Em Andamento';
        });
    
        return $finishes;
    }

    public function toggle(Request $request)
    {
        Like::toggleLike($request->user_id, $request->promisse_id);
        
        return response()->json(['message' => 'Ação realizada com sucesso']);
    }
    
}
