<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Permission::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('group_name', 'like', "%{$search}%");
        }
        
        $allGrouped = $query->get()->groupBy('group_name');
        
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 7;
        $groupedPermissions = new \Illuminate\Pagination\LengthAwarePaginator(
            $allGrouped->forPage($page, $perPage),
            $allGrouped->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );
        
        return view('admin.permissions.index', compact('groupedPermissions', 'search'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Hak Akses (Permission) berhasil ditambahkan.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Hak Akses (Permission) berhasil dihapus.');
    }
}
