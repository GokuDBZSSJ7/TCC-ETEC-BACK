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
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            if ($validations->fails()) {
                return response()->json(['message' => 'Erro de validação', 'errors' => $validations->errors()], 422);  // Alterado para 422 e incluído detalhes dos erros
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'Usuario',
                'type' => 1,
                'image_url' => $request->image_url,
                'party_id' => $request->party_id,
                'city_id' => $request->city_id,
                'state_id' => $request->state_id,
            ]);

            return response()->json($user);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor', 'error' => $e->getMessage()], 500);  // Formatação correta do erro
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

    public function upgradeToCandidate($id)
    {
        try {
            $user = User::find($id);
            $user->type = 2;
            $user->role = `Político`;
            $user->save();


            return response()->json($user);
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }

    public function upgradeToLeader($id, $partyName)
    {
        try {
            $user = User::findOrFail($id);
            $user->type = 3;
            $user->role = "Líder do partido $partyName";
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }

    public function returnToUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->type = 1;
            $user->role = 'Usuario';
        } catch (Exception $e) {
            return response()->json(['message' => 'error', $e], 500);
        }
    }

    public function getPoliticians()
    {
        $politicians = User::where('type', 2)->get();
        $politicians->load('party');

        return response()->json($politicians);
    }
}
