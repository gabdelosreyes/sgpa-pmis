<?php

namespace App\Livewire\Personnel;

use App\Models\Personnel;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
#[Title('Personnel Directory')]
class Index extends Component
{
    // Modals
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showViewModal = false;
    public bool $showDeactivatedModal = false;
    public bool $showDeleteDialog = false;

    // State
    public ?Personnel $activePersonnel = null;
    public $deleteId = null;

    // Form fields
    public $first_name = '';
    public $last_name = '';
    public $age = null;
    public $gender = null;
    public $department = null;
    public $date_started = null;
    public $is_active = true;

    public function resetForm()
    {
        $this->reset(['first_name', 'last_name', 'age', 'gender', 'department', 'date_started', 'is_active', 'activePersonnel']);
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'gender' => 'required|in:Male,Female',
            'department' => 'required|string|max:255',
            'date_started' => 'required|date',
            'is_active' => 'boolean',
        ]);

        Personnel::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'department' => $this->department,
            'date_started' => $this->date_started,
            'is_active' => $this->is_active,
        ]);

        $this->showCreateModal = false;
        $this->dispatch('pg:eventRefresh-personnel-table');
        session()->flash('success', 'Personnel added successfully.');
    }

    #[On('view-personnel')]
    public function viewPersonnel($id)
    {
        $this->activePersonnel = Personnel::findOrFail($id);
        $this->showViewModal = true;
    }

    #[On('edit-personnel')]
    public function editPersonnel($id)
    {
        $this->resetForm();
        $this->activePersonnel = Personnel::findOrFail($id);
        
        $this->first_name = $this->activePersonnel->first_name;
        $this->last_name = $this->activePersonnel->last_name;
        $this->age = $this->activePersonnel->age;
        $this->gender = $this->activePersonnel->gender;
        $this->department = $this->activePersonnel->department;
        $this->date_started = $this->activePersonnel->date_started ? $this->activePersonnel->date_started->format('Y-m-d') : null;
        $this->is_active = (bool) $this->activePersonnel->is_active;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'gender' => 'required|in:Male,Female',
            'department' => 'required|string|max:255',
            'date_started' => 'required|date',
            'is_active' => 'boolean',
        ]);

        $this->activePersonnel->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'department' => $this->department,
            'date_started' => $this->date_started,
            'is_active' => $this->is_active,
        ]);

        $this->showEditModal = false;
        $this->dispatch('pg:eventRefresh-personnel-table');
        $this->dispatch('pg:eventRefresh-deactivated-personnel-table');
        session()->flash('success', 'Personnel updated successfully.');
    }

    #[On('delete-personnel')]
    public function deletePersonnel($id)
    {
        // Deactivate action from main table
        $personnel = Personnel::findOrFail($id);
        $personnel->update(['is_active' => false]);
        $this->dispatch('pg:eventRefresh-personnel-table');
        $this->dispatch('pg:eventRefresh-deactivated-personnel-table');
        session()->flash('success', 'Personnel deactivated.');
    }

    #[On('activate-personnel')]
    public function activatePersonnel($id)
    {
        $personnel = Personnel::findOrFail($id);
        $personnel->update(['is_active' => true]);
        $this->dispatch('pg:eventRefresh-personnel-table');
        $this->dispatch('pg:eventRefresh-deactivated-personnel-table');
        session()->flash('success', 'Personnel reactivated.');
    }

    #[On('permanent-delete-personnel')]
    public function triggerDeleteDialog($id)
    {
        $this->deleteId = $id;
        $this->showDeleteDialog = true;
    }

    public function confirmPermanentDelete()
    {
        if ($this->deleteId) {
            Personnel::findOrFail($this->deleteId)->delete();
            $this->showDeleteDialog = false;
            $this->deleteId = null;
            $this->dispatch('pg:eventRefresh-deactivated-personnel-table');
            session()->flash('success', 'Personnel permanently deleted.');
        }
    }

    public function render()
    {
        return view('livewire.personnel.index')
            ->layoutData(['header' => 'Personnel Directory']);
    }
}
