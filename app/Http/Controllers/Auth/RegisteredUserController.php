<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Person;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
    
            $person = new Person($request->all());
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $person->save() && $user->person()->associate($person)->save() && ($token = $user->createToken($user->email)) && $user->assignRole(Role::findByName(RoleEnum::ADMIN())) && $user->admin()->save(new Admin());
    
            event(new Registered($user));
    
            Auth::login($user);
    
            return response()->json([
                'token' => $token->plainTextToken,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
