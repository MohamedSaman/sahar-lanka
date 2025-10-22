<?php

namespace App\Livewire\Admin;

use App\Models\brand;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Product Brands')]
class Brands extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $brand_name, $notes, $search = '';
    public $showAddModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $editingBrandId = null;
    public $deletingBrandId = null;

    protected $rules = [
        'brand_name' => 'required|string|min:3|max:255',
        'notes' => 'nullable|string|max:500',
    ];

    // Reset pagination when search updates
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleAddModal()
    {
        $this->reset(['brand_name', 'notes']);
        $this->resetValidation();
        $this->showAddModal = true;
    }

    public function toggleEditModal($brandId)
    {
        $brands = brand::findOrFail($brandId);
        $this->editingBrandId = $brands->id;
        $this->brand_name = $brands->brand_name;
        $this->notes = $brands->notes;
        $this->showEditModal = true;
    }

    public function toggleDeleteModal($brandId)
    {
        $this->deletingBrandId = $brandId;
        $this->showDeleteModal = true;
    }

    public function save()
    {
        $this->validate();

        brand::create([
            'brand_name' => $this->brand_name,
            'notes' => $this->notes,
        ]);

        $this->showAddModal = false;
        $this->reset(['brand_name', 'notes']);
        $this->js('swal.fire("Success", "Brand added successfully!", "success")');
    }

    public function update()
    {
        $this->validate();

        $brands = brand::findOrFail($this->editingBrandId);
        $brands->update([
            'name' => $this->brand_name,
            'description' => $this->notes,
        ]);

        $this->showEditModal = false;
        $this->reset(['brand_name', 'notes', 'editingBrandId']);
        $this->js('swal.fire("Success", "Brand updated successfully!", "success")');
    }

    public function delete()
    {
        $brands = brand::findOrFail($this->deletingBrandId);
        $brands->delete();

        $this->showDeleteModal = false;
        $this->deletingBrandId = null;
        $this->js('swal.fire("Success", "Brand deleted successfully", "success")');
    }
    public function render()
    {
        $brands = brand::query()
            ->where('brand_name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.admin.brands', compact('brands'));
    }
}
