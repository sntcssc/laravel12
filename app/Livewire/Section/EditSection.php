<?php

namespace App\Livewire\Section;

use App\Models\Programme;
use App\Services\SectionService;
use App\Models\Section;
use Livewire\Component;

class EditSection extends Component
{
    public $section;
    public $name;
    public $programme_id;

    protected $rules = [
        'name' => 'required|string|max:255',
        'programme_id' => 'nullable|exists:programmes,id',
    ];

    public function mount($id, SectionService $sectionService)
    {
        $this->section = $sectionService->find($id);
        $this->name = $this->section->name;
        $this->programme_id = $this->section->programme_id;
    }

    public function render()
    {
        $programmes = Programme::select('id', 'name')->get();
        return view('livewire.section.edit-sections', compact('programmes'));
        // ->layout('layouts.app');
    }

    public function save(SectionService $sectionService)
    {
        $this->validate();

        try {
            $sectionService->update($this->section, [
                'name' => $this->name,
                'programme_id' => $this->programme_id,
            ]);
            session()->flash('message', 'Section updated successfully.');
            return redirect()->route('sections.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}