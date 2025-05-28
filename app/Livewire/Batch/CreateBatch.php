<?php

namespace App\Livewire\Batch;

use App\Services\BatchService;
use Livewire\Component;

class CreateBatch extends Component
{
    public $year;
    public $name;

    protected $rules = [
        'year' => 'required|integer|min:2000|max:2100',
        'name' => 'required|string|max:255',
    ];

    public function render()
    {
        return view('livewire.batch.create-batch');
    }

    public function save(BatchService $batchService)
    {
        $this->validate();

        try {
            $batchService->create([
                'year' => $this->year,
                'name' => $this->name,
            ]);
            session()->flash('message', 'Batch created successfully.');
            return redirect()->route('batches.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}