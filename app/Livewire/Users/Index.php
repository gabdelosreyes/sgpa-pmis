<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;

use Livewire\Attributes\On;
use App\Models\User;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.app', ['header' => 'User Management'])]
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
    
    // Roles
    public $selectedRoles = [];
    public $availableRoles = [];

    public function mount()
    {
        $this->availableRoles = Role::pluck('name')->toArray();
    }

    #[On('view-user')]
    public function viewUser($id)
    {
        $this->activeUser = User::findOrFail($id);
        $this->showViewModal = true;
    }

    #[On('edit-user')]
    public function editUser($id)
    {
        $this->activeUser = User::findOrFail($id);
        $this->selectedRoles = $this->activeUser->getRoleNames()->toArray();
        $this->showEditRolesModal = true;
    }

    public function updateRoles()
    {
        if ($this->activeUser) {
            $this->activeUser->syncRoles($this->selectedRoles);
            $this->showEditRolesModal = false;
            $this->dispatch('pg:eventRefresh-users-table');
            session()->flash('success', 'User roles updated successfully.');
        }
    }

    #[On('deactivate-user')]
    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => false]);
        $this->dispatch('pg:eventRefresh-users-table');
        $this->dispatch('pg:eventRefresh-deactivated-users-table');
        session()->flash('success', 'User deactivated.');
    }

    #[On('reactivate-user')]
    public function reactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);
        $this->dispatch('pg:eventRefresh-users-table');
        $this->dispatch('pg:eventRefresh-deactivated-users-table');
        session()->flash('success', 'User reactivated.');
    }

    #[On('delete-user')]
    public function triggerDeleteDialog($id)
    {
        $this->deleteId = $id;
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
