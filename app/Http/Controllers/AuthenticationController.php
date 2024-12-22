<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    /** 
     * Register Method
     *taks in name, email, password and role
     */
    public function register(Request $request)
    {
        try {
            // Validating the incoming request
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|min:6|max:255',
                'role' => 'in:admin,customer',
            ]);

            // Creatinga new user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'role' => $request->role ?? 'customer',
            ]);

            // Generate an API token for the user
            $token = $user->createToken('user')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }


    // Login Method
    public function login(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'email' => 'required|email|exists:users,email|max:255',
                'password' => 'required|min:6|max:255',
            ]);

            // Check if user exists and passwords match
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Generate API token
            $token = $user->createToken('user')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

/**
 * Logout Method
 * Invalidates a token
 */
public function logout(Request $request)
{
try {
// Revoke the user's API token (stateless)
if (Auth::check()) {
Auth::user()->tokens->each(function ($token) {
$token->delete();
});
}

return response()->json([
'message' => 'Successfully logged out.',
], 200);
} catch (\Exception $e) {
return response()->json([
'error' => 'An unexpected error occurred. Please try again later.',
], 500);
}
}
}