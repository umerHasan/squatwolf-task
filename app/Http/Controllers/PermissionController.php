<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $this->authorize('view-permission');
        
        $permissions = Permission::get();

        return response()->json([
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePermissionRequest $request): JsonResponse
    {
        $this->authorize('create-permission');

        $data = $request->all();

        $permission = Permission::create($data);

        return response()->json([
            'permission' => $permission
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $this->authorize('view-permission');

        return response()->json([
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $this->authorize('update-permission');

        $data = $request->all();

        $permission->update($data);

        return response()->json([
            'permission' => $permission
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete-permission');

        $permission->delete();

        return response()->json([], 204);
    }
}
