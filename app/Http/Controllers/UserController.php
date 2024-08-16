<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json($users);
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
                'name' => 'required|string',
                'email' => 'required|unique',
                'password' => 'required|min:6',
                
            ]);

            if($validations->fails()) {
                return response()->json(['message' => 'Erro de validação'], 500);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'usuario',
                'type' => 'user',
                'image_url' => $request->image_url,
                'party_id' => $request->party_id
            ]);

            return response()->json($user);

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
            $user = User::find($id);
            return response()->json($user);
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
            $user = User::find($id);
            $imagePath = null;
            if ($request->has('image_url') && !empty($request->image_url)) {
                $imageData = $request->image_url;
                $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
                $imageName = time() . '.jpg';
                $imagePath = 'images/users/' . $imageName;
                Storage::disk('public')->put($imagePath, base64_decode($base64Image));
            }

            $data = $request->all();
            if ($imagePath) {
                $data['image_url'] = $imagePath;
            }

            $user->update($data);

            return response()->json($user);
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
            $user = User::find($id);
            $user->delete();

            return response()->json(['message' => 'Usuario deletado com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }
}
