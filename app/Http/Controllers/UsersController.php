<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{

    public function index()
    {
        $users = app('db')->table('users')->get();
        return response()->json($users);
    }

    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'full_name' => 'required',
                'username' => 'required|min:5',
                'email' => 'required|email',
                'password' => 'required|min:5',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        try {
            $id = app('db')->table('users')->insertGetId([
                'full_name' => $request->input('full_name'),
                'username' => strtolower(trim($request->input('username'))),
                'email' => strtolower(trim($request->input('email'))),
                'password' => app('hash')->make($request->input('password')),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $user = app('db')->table('users')->select('full_name', 'username', 'email')->where('id', $id)->first();
            return response()->json([
                'id' => $id,
                'full_name' => $user->full_name,
                'username' => $user->username,
                'email' => $user->email,

            ], 201);
        } catch (\PDOException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }

    }

    public function authenticate(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|min:5',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
        $token = app('auth')->attempt($request->only('email', 'password'));

        if ($token) {
            return response()->json([
                'success' => true,
                'message' => 'User Authenticated',
                'token' => $token,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 400);

    }
}
