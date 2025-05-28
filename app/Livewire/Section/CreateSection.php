<?php

namespace App\Livewire\Section;

use App\Models\Programme;
use App\Services\SectionService;
use Livewire\Component;

class CreateSection extends Component
{
    public $name;
    public $programme_id;

    protected $rules = [
        'name' => 'required|string|max:255',
        'programme_id' => 'nullable|exists:programmes,id',
    ];

    public function render()
    {
        $programmes = Programme::select('id', 'name')->get();
        return view('livewire.section.create-sections', compact('programmes'));
        // ->layout('layouts.app');
    }

    public function save(SectionService $sectionService)
    {
        $this->validate();

        try {
            $sectionService->create([
                'name' => $this->name,
                'programme_id' => $this->programme_id,
            ]);
            session()->flash('message', 'Section created successfully.');
            return redirect()->route('sections.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}