<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\UsersExport;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $selectedUsers = [];
    public $showTrashed = false;

    protected $queryString = ['search', 'status', 'sortBy', 'sortDirection', 'showTrashed'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedShowTrashed()
    {
        $this->resetPage();
    }

    public function render(UserService $userService)
    {
        $filters = [
            'search' => $this->search,
            'status' => $this->status,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
            'per_page' => $this->perPage,
        ];

        $users = $userService->getAllUsers($filters, $this->showTrashed);

        return view('livewire.user.user-list', [
            'users' => $users,
        ]);
    }

    public function toggleSort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function delete($userId, UserService $userService)
    {
        $user = $userService->findById($userId);
        $userService->deleteUser($user);
        session()->flash('success', 'User deleted successfully.');
        $this->resetPage();
    }

    public function bulkDelete(UserService $userService)
    {
        $userService->bulkDelete($this->selectedUsers);
        $this->selectedUsers = [];
        session()->flash('success', 'Selected users deleted successfully.');
        $this->resetPage();
    }

    public function restore($userId, UserService $userService)
    {
        $userService->restoreUser($userId);
        session()->flash('success', 'User restored successfully.');
        $this->resetPage();
    }

    public function exportPdf(UserService $userService)
    {
        $filters = [
            'search' => $this->search,
            'status' => $this->status,
        ];
        $users = $userService->exportUsers($filters);
        $pdf = Pdf::loadView('exports.users-pdf', ['users' => $users]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'users.pdf');
    }

    public function exportExcel(UserService $userService)
    {
        $filters = [
            'search' => $this->search,
            'status' => $this->status,
        ];
        return Excel::download(new UsersExport($userService, $filters), 'users.xlsx');
    }
}