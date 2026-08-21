<?php

namespace App\Livewire\RolesPermissions;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('components.layouts.app', ['header' => 'Roles & Permissions'])]
#[Title('Roles & Permissions')]
class Index extends Component
{
    // Role State
    public bool $showRoleModal = false;
    public bool $showDeleteRoleModal = false;
    public $roleId = null;
    public $roleName = '';
    public $rolePermissions = [];

    // Permission State
    public bool $showPermissionModal = false;
    public bool $showDeletePermissionModal = false;
    public $permissionId = null;
    public $permissionName = '';

    public $allPermissions = [];

    public function mount()
    {
        $this->allPermissions = Permission::pluck('name')->toArray();
    }

    // --- Role Methods ---

    public function openRoleModal($id = null)
    {
        abort_unless(auth()->user()->can($id ? 'edit roles' : 'add roles'), 403);
        $this->resetValidation();
        $this->roleId = $id;
        
        if ($id) {
            $role = Role::findById($id);
            $this->roleName = $role->name;
            $this->rolePermissions = $role->permissions->pluck('name')->toArray();
        } else {
            $this->roleName = '';
            $this->rolePermissions = [];
        }
        
        $this->showRoleModal = true;
    }

    #[On('edit-role')]
    public function editRole($id)
    {
        $this->openRoleModal($id);
    }

    public function saveRole()
    {
        abort_unless(auth()->user()->can($this->roleId ? 'edit roles' : 'add roles'), 403);
        
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
        ]);

        if ($this->roleId) {
            $role = Role::findById($this->roleId);
            $role->update(['name' => $this->roleName]);
        } else {
            $role = Role::create(['name' => $this->roleName]);
        }

        if ($this->roleName === 'Admin') {
            $this->rolePermissions = $this->allPermissions;
        }

        $role->syncPermissions($this->rolePermissions);

        $this->showRoleModal = false;
        $this->dispatch('pg:eventRefresh-roles-table');
        session()->flash('success', 'Role saved successfully.');
    }

    #[On('delete-role')]
    public function triggerDeleteRole($id)
    {
        abort_unless(auth()->user()->can('delete roles'), 403);
        $role = Role::findById($id);
        if (in_array($role->name, ['Admin', 'User'])) {
            session()->flash('error', 'Cannot delete default system roles.');
            return;
        }

        $this->roleId = $id;
        $this->showDeleteRoleModal = true;
    }

    public function confirmDeleteRole()
    {
        abort_unless(auth()->user()->can('delete roles'), 403);
        if ($this->roleId) {
            Role::findById($this->roleId)->delete();
            $this->showDeleteRoleModal = false;
            $this->roleId = null;
            $this->dispatch('pg:eventRefresh-roles-table');
            session()->flash('success', 'Role deleted.');
        }
    }

    // --- Permission Methods ---

    public function openPermissionModal($id = null)
    {
        $this->resetValidation();
        $this->permissionId = $id;
        
        if ($id) {
            $permission = Permission::findById($id);
            $this->permissionName = $permission->name;
        } else {
            $this->permissionName = '';
        }
        
        $this->showPermissionModal = true;
    }

    #[On('edit-permission')]
    public function editPermission($id)
    {
        $this->openPermissionModal($id);
    }

    public function savePermission()
    {
        $this->validate([
            'permissionName' => 'required|string|max:255|unique:permissions,name,' . $this->permissionId,
        ]);

        if ($this->permissionId) {
            $permission = Permission::findById($this->permissionId);
            $permission->update(['name' => $this->permissionName]);
        } else {
            Permission::create(['name' => $this->permissionName]);
        }

        $this->allPermissions = Permission::pluck('name')->toArray();
        $this->showPermissionModal = false;
        $this->dispatch('pg:eventRefresh-permissions-table');
        session()->flash('success', 'Permission saved successfully.');
    }

    #[On('delete-permission')]
    public function triggerDeletePermission($id)
    {
        $this->permissionId = $id;
        $this->showDeletePermissionModal = true;
    }

    public function confirmDeletePermission()
    {
        if ($this->permissionId) {
            Permission::findById($this->permissionId)->delete();
            $this->showDeletePermissionModal = false;
            $this->permissionId = null;
            $this->allPermissions = Permission::pluck('name')->toArray();
            $this->dispatch('pg:eventRefresh-permissions-table');
            session()->flash('success', 'Permission deleted.');
        }
    }

    public function render()
    {
        return view('livewire.roles-permissions.index');
    }
}
