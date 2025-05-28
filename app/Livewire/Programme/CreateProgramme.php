<?php

namespace App\Livewire\Programme;

use App\Services\ProgrammeService;
use Livewire\Component;

class CreateProgramme extends Component
{
    public $name;
    public $description;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function render()
    {
        return view('livewire.programme.create-programme');
    }

    public function save(ProgrammeService $programmeService)
    {
        $this->validate();

        try {
            $programmeService->create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Programme created successfully.');
            return redirect()->route('programmes.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}