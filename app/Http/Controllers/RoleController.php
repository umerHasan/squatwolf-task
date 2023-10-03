<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $this->authorize('view-role');

        $roles = Role::with('permissions')->get();

        return response()->json([
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request): JsonResponse
    {
        $this->authorize('create-role');

        $data = $request->all();

        DB::beginTransaction();

        try {
            $role = Role::create($data);
            $role->syncPermissions($data['permissions']);
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Role cannot be created'
            ], 500);
        }

        return response()->json([
            'role' => $role
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        $this->authorize('view-role');

        $role->load('permissions');

        return response()->json([
            'role' => $role
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRoleRequest $request, Role $role): JsonResponse
    {
        $this->authorize('update-role');

        $data = $request->all();

        DB::beginTransaction();

        try {
            $role->update($data);
            $role->syncPermissions($data['permissions']);
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Role cannot be updated'
            ], 500);
        }

        return response()->json([
            'role' => $role
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete-role');

        // check the role has any users, if yes display the message to the user to delete/unassign the role first
        $role->delete();

        return response()->json([], 204);
    }
}
