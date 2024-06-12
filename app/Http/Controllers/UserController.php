<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\UserRequest;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Person;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = User::when($request->role, function (Builder $query, string $role) {
                $role = RoleEnum::from($role);
                if (RoleEnum::ADMIN()->equals($role)) {
                    $relation = 'admin';
                } elseif (RoleEnum::EMPLOYEE()->equals($role)) {
                    $relation = 'employee';
                }
                $query->has($relation)->with($relation);
            })
            ->when($request->filter, function (Builder $query, array $filter) {
                QueryBuilder::for($query)
                    ->allowedFilters([
                        AllowedFilter::callback('any', function (Builder $query, $value) {
                            $query->whereRelation('person', 'first_name', 'like', "%$value%")
                                ->orWhereRelation('person', 'last_name', 'like', "%$value%")
                                ->orWhereRelation('person', 'cin', 'like', "%$value%")
                                ->orWhereRelation('person', 'passport', 'like', "%$value%")
                                ->orWhereRelation('person', 'phone', 'like', "%$value%")
                                ->orWhereRelation('person', 'contact_email', 'like', "%$value%")
                                ->orWhereRelation('person', 'city', 'like', "%$value%")
                                ->orWhere('name', 'like', "%$value%")
                                ->orWhere('email', 'like', "%$value%");
                        }),
                    ]);
            })
            ->with([
                'person',
                'admin',
                'employee',
                'roles.permissions',
                'permissions',
            ])
            ->get();
            return response()->json([
                'result' => $users,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $person = Person::find($request->person);
            $user = new User($request->merge([
                'password' => Hash::make('greenwheels@' . now()->year),
                'name' => Str::snake($person->first_name . ' ' . $person->last_name),
            ]) ->all());
            if ($user->person()->associate($person) && $user->save()) {
                $token = $user->createToken($user->email)->plainTextToken;
                $role = RoleEnum::from($request->role);
                if ($dbRole = Role::findByName($role->value, 'web')) {
                    $user->assignRole($dbRole)->refresh();
                    $user->syncPermissions($request->permissions);
                    $result = $user;
                    $msg = Str::ucfirst(__('user was successfully added'));
                    $status = 200;
                } else {
                    throw new Exception(Str::ucfirst(__('role not found. Maybe you need to seed the DB using `setup:roles` artisan command')));
                }
            } else {
                $result = null;
                $token = null;
                $msg = Str::ucfirst(__('user was not successfully added'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'token' => $token,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            return response()->json([
                'result' => $user->load([
                    'admin',
                    'employee',
                    'person',
                    'roles',
                ]),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function userByEmail(string $email)
    {
        try {
            $user = User::where('email', $email)
            ->with([
                'person',
                'admin',
                'employee',
            ])
            ->first();
            if ($user) {
                $result = $user;
                $msg = Str::ucfirst(__('user was successfully fetched'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('user was not successfully fetched'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            if ($user->update($request->mergeIfMissing([
                'name' => Str::snake($request->first_name . ' ' . $request->last_name),
            ])->all())) {
                $request->whenFilled('role', function (string $role) use ($user) {
                    $roleEnum = RoleEnum::from($role);
                    if ($dbRole = Role::findByName($roleEnum->value, 'web')) {
                        $user->syncRoles($dbRole)->refresh();
                    } else {
                        throw new Exception(Str::ucfirst(__('role not found. Maybe you need to seed the DB using `setup:roles` artisan command')));
                    }
                });
                $user->syncPermissions($request->permissions);
                $result = $user->load([
                    'person',
                    'admin',
                    'employee',
                ]);
                $msg = Str::ucfirst(__('user was successfully updated'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('user was not successfully updated'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            if ($user->delete()) {
                $result = $user->load([
                    'person',
                    'admin',
                    'employee',
                ]);
                $msg = Str::ucfirst(__('user was successfully deleted'));
                $status = 200;
            } else {
                $result = null;
                $msg = Str::ucfirst(__('user was not successfully deleted'));
                $status = 500;
            }
            return response()->json([
                'result' => $result,
                'msg' => $msg,
                'status' => $status,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
