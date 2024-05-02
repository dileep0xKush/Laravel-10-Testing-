<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
        $addUser = new User();
        $addUser->name = $request->name;
        $addUser->email = $request->email;
        $addUser->password = bcrypt($request->password);
        $addUser->save();
        $token = $addUser->createToken('API Token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $addUser]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        if (Hash::check($request->email, $user->password)) {

            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'user' => $user, 'token' => $token]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function logout(Request $request)
    {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();
    
        return response()->json(['message' => 'Logged out successfully']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
