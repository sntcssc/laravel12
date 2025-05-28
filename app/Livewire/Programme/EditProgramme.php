<?php

namespace App\Livewire\Programme;

use App\Services\ProgrammeService;
use App\Models\Programme;
use Livewire\Component;

class EditProgramme extends Component
{
    public $programme;
    public $name;
    public $description;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    // public function mount($id, ProgrammeService $programmeService)
    // {
    //     $this->programme = $programmeService->find($id);
    //     $this->name = $this->programme->name;
    //     $this->description = $this->programme->description;
    // }

    public function mount(Programme $programme)
    {
        $this->programme = $programme;
        $this->name = $this->programme->name;
        $this->description = $this->programme->description;
    }

    public function render()
    {
        return view('livewire.programme.edit-programme');
    }

    public function save(ProgrammeService $programmeService)
    {
        $this->validate();

        try {
            $programmeService->update($this->programme, [
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Programme updated successfully.');
            return redirect()->route('programmes.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}