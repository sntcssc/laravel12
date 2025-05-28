<?php

namespace App\Livewire\Batch;

use App\Services\BatchService;
use App\Models\Batch;
use Livewire\Component;

class EditBatch extends Component
{
    public $batch;
    public $year;
    public $name;

    protected $rules = [
        'year' => 'required|integer|min:2000|max:2100',
        'name' => 'required|string|max:255',
    ];

    // public function mount($id, BatchService $batchService)
    // {
    //     $this->batch = $batchService->find($id);
    //     $this->year = $this->batch->year;
    //     $this->name = $this->batch->name;
    // }

    public function mount(Batch $batch)
    {
        $this->batch = $batch;
        $this->year = $this->batch->year;
        $this->name = $this->batch->name;
    }

    public function render()
    {
        return view('livewire.batch.edit-batch');
    }

    public function save(BatchService $batchService)
    {
        $this->validate();

        try {
            $batchService->update($this->batch, [
                'year' => $this->year,
                'name' => $this->name,
            ]);
            session()->flash('message', 'Batch updated successfully.');
            return redirect()->route('batches.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}