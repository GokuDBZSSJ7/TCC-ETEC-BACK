<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $parties = Party::all();
            return response()->json($parties);
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

            $imagePath = null;
            if ($request->has('image_url') && !empty($request->image_url)) {
                $imageData = $request->image_url;
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageName = time() . '.jpg';
                $imagePath = 'images/parties/' . $imageName;
                Storage::disk('public')->put($imagePath, base64_decode($base64Image));
            }

            $data = $request->all();
            if ($imagePath) {
                $data['image_url'] = $imagePath;
            }

            $party = Party::create($data);

            return response()->json($party);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
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

            $imagePath = null;
            if ($request->has('image_url') && !empty($request->image_url)) {
                $imageData = $request->image_url;
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageName = time() . '.jpg';
                $imagePath = 'images/parties/' . $imageName;
                Storage::disk('public')->put($imagePath, base64_decode($base64Image));
            }

            $data = $request->all();
            if ($imagePath) {
                $data['image_url'] = $imagePath;
            }

            $party = Party::findOrFail($id);
            $party->update($data);

            return response()->json($party);
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
            $party = Party::find($id);
            $party->delete();

            return response()->json("Deletado com sucesso!");
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }
}
