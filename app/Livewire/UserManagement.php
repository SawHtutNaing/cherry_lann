<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserManagement extends Component
{
    public $users;

    // protected $listeners = [
    //     'userUpdated' => '$refresh',
    // ];

    public function mount()
    {
        $this->users = User::all();
        if(auth()->user()->role != 'admin'){
            return redirect()->route('dashboard');
        }

    }

    public function toggleStatus(User $user)
    {
        $user->status = !$user->status;
        $user->save();
        $this->users = User::all();
    }

    public function deleteUser(User $user)
    {
        if (auth()->user()->role != 'admin') {
            session()->flash('error', 'You are not authorized to delete users.');
            return;
        }

        $user->delete();
        session()->flash('message', 'User deleted successfully.');
        $this->users = User::all();
    }
}
