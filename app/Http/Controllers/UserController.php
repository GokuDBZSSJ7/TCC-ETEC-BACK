<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promisse;
use App\Models\User;
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
        return $this->handleRequest(function() {
            $users = User::all();
            $users->load('party');
            return response()->json($users);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->handleRequest(function() use ($request) {
            $this->validateUser($request);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'Usuario',
                'type' => 1,
                'image_url' => $request->image_url,
                'party_id' => $request->party_id,
                'city_id' => $request->city_id,
            ]);

            return response()->json($user);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->handleRequest(function() use ($id) {
            $user = User::find($id);
            return response()->json($user);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return $this->handleRequest(function() use ($request, $id) {
            $user = User::find($id);
            $imagePath = $this->handleImageUpload($request->image_url);

            $data = $request->all();
            if ($imagePath) {
                $data['image_url'] = $imagePath;
            }

            $user->update($data);
            return response()->json($user);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->handleRequest(function() use ($id) {
            $user = User::find($id);
            $user->delete();

            return response()->json(['message' => 'Usuario deletado com sucesso!']);
        });
    }

    public function upgradeToCandidate(Request $request)
    {
        return $this->handleRequest(function() use ($request) {
            $user = User::find($request->id);
            $user->type = 2;
            $user->role = $request->role;
            $user->party_id = $request->party_id;
            $user->save();

            return response()->json($user);
        });
    }

    public function upgradeToLeader($id, $partyName)
    {
        return $this->handleRequest(function() use ($id, $partyName) {
            $user = User::findOrFail($id);
            $user->type = 3;
            $user->role = "Líder do partido $partyName";
            $user->save();

            return response()->json($user);
        });
    }

    public function returnToUser($id)
    {
        return $this->handleRequest(function() use ($id) {
            $user = User::findOrFail($id);
            $user->type = 1;
            $user->role = 'Usuario';
            $user->save();

            return response()->json($user);
        });
    }

    public function getPoliticians()
    {
        return $this->handleRequest(function() {
            $politicians = User::where('type', 2)->get();
            $politicians->load('party');
            return response()->json($politicians);
        });
    }

    public function getUsers()
    {
        return $this->handleRequest(function() {
            $users = User::where('type', 1)->get();
            return response()->json($users);
        });
    }

    public function filterPoliticians(Request $request)
    {
        return $this->handleRequest(function() use ($request) {
            $users = User::with(['state', 'party', 'city'])->where('type', 2);

            $filters = ['name', 'state_id', 'city_id', 'party_id'];
            foreach ($filters as $filter) {
                if ($request->has($filter) && $request->$filter !== null) {
                    $users->where($filter, $request->$filter);
                }
            }

            return $users->get();
        });
    }

    /**
     * Handle common request processing and error handling.
     */
    protected function handleRequest(callable $callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Validate user data.
     */
    protected function validateUser(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validations->fails()) {
            throw new \Illuminate\Validation\ValidationException($validations);
        }
    }

    /**
     * Handle image upload.
     */
    protected function handleImageUpload($imageData)
    {
        if ($imageData && !empty($imageData)) {
            $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
            $imageName = time() . '.jpg';
            $imagePath = 'images/users/' . $imageName;
            Storage::disk('public')->put($imagePath, base64_decode($base64Image));

            return $imagePath;
        }
        return null;
    }

    public function showMyPromisses($id) {
        $promisses = Promisse::all()->where('political_id', $id);

        return $promisses;
    }
}
