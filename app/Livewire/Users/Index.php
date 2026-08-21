<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Models\User;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.app', ['header' => 'User Management'])]
#[Title('User Management')]
class Index extends Component
{
    // Modals
    public bool $showViewModal = false;
    public bool $showEditRolesModal = false;
    public bool $showDeactivatedModal = false;
    public bool $showDeleteDialog = false;

    // State
    public ?User $activeUser = null;
    public $deleteId = null;
    
    // Roles & Permissions
    public $selectedRoles = [];
    public $selectedPermissions = [];
    public $availableRoles = [];
    public $availablePermissions = [];

    public function mount()
    {
        $this->availableRoles = Role::pluck('name')->toArray();
        $this->availablePermissions = \Spatie\Permission\Models\Permission::pluck('name')->toArray();
    }

    private function resolveId($id)
    {
        return is_array($id) ? ($id['id'] ?? null) : $id;
    }

    #[On('view-user')]
    public function viewUser($id)
    {
        abort_unless(auth()->user()->can('view users'), 403);
        $this->activeUser = User::findOrFail($this->resolveId($id));
        $this->showViewModal = true;
    }

    #[On('edit-user')]
    public function editUser($id)
    {
        abort_unless(auth()->user()->can('edit users'), 403);
        $this->activeUser = User::findOrFail($this->resolveId($id));
        $this->selectedRoles = $this->activeUser->getRoleNames()->toArray();
        $this->selectedPermissions = $this->activeUser->getDirectPermissions()->pluck('name')->toArray();
        $this->showEditRolesModal = true;
    }

    public function updateRoles()
    {
        abort_unless(auth()->user()->can('edit users'), 403);
        if ($this->activeUser) {
            $this->activeUser->syncRoles($this->selectedRoles);
            $this->activeUser->syncPermissions($this->selectedPermissions);
            $this->showEditRolesModal = false;
            $this->dispatch('pg:eventRefresh-users-table');
            session()->flash('success', 'User roles and permissions updated successfully.');
        }
    }

    #[On('deactivate-user')]
    public function deactivateUser($id)
    {
        abort_unless(auth()->user()->can('delete users'), 403);
        $user = User::findOrFail($this->resolveId($id));
        $user->update(['is_active' => false]);
        $this->dispatch('pg:eventRefresh-users-table');
        $this->dispatch('pg:eventRefresh-deactivated-users-table');
        session()->flash('success', 'User deactivated.');
    }

    #[On('reactivate-user')]
    public function reactivateUser($id)
    {
        abort_unless(auth()->user()->can('edit users'), 403);
        $user = User::findOrFail($this->resolveId($id));
        $user->update(['is_active' => true]);
        $this->dispatch('pg:eventRefresh-users-table');
        $this->dispatch('pg:eventRefresh-deactivated-users-table');
        session()->flash('success', 'User reactivated.');
    }

    #[On('delete-user')]
    public function triggerDeleteDialog($id)
    {
        abort_unless(auth()->user()->can('delete users'), 403);
        $this->deleteId = $this->resolveId($id);
        $this->showDeleteDialog = true;
    }

    public function confirmPermanentDelete()
    {
        if ($this->deleteId) {
            User::findOrFail($this->deleteId)->delete();
            $this->showDeleteDialog = false;
            $this->deleteId = null;
            $this->dispatch('pg:eventRefresh-deactivated-users-table');
            session()->flash('success', 'User permanently deleted.');
        }
    }

    public function render()
    {
        return view('livewire.users.index');
    }
}
