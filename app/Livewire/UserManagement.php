<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserManagement extends Component
{
    public $users;

    // protected $listeners = [
    //     'userUpdated' => '$refresh',
    // ];

    public function mount()
    {
        $this->users = User::all();
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            return redirect()->route('dashboard');
        }

    }

    public function toggleStatus(User $user)
    {
       if (!in_array(auth()->user()->role, ['admin', 'super_admin']) || auth()->id() === $user->id) {
            return;
        }

        $user->status = !$user->status;
        $user->save();
        $this->users = User::all();
        session()->flash('message', 'User status updated successfully.');
    }

    public function delete(User $user)
    {
              if (!in_array(auth()->user()->role, ['admin', 'super_admin']) || auth()->id() === $user->id) {
            return;
        }


        foreach ($user->dataInputs as $dataInput) {
            // if ($dataInput->client_side_image) {
            //     Storage::disk('public')->delete($dataInput->client_side_image);
            // }
            // if ($dataInput->service_side_image) {
            //     Storage::disk('public')->delete($dataInput->service_side_image);
            // }
            $dataInput->delete();
        }

        $user->delete();
        $this->users = User::all();
        session()->flash('message', 'User and all associated data deleted successfully.');
    }

    public function render()
    {
        return view('livewire.user-management');
    }
}
