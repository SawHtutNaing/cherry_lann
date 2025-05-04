<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserForm extends Component
{
    public $name;
    public $email;
    public $password;
    public $role;
    public $userId;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->userId),
            ],
            'password' => $this->userId ? 'nullable|string|min:8' : 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ];
    }


    public function mount($userId = null)
    {
        if ($userId) {
            $user = User::findOrFail($userId);

            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
        }
    }

    public function save()
    {


        $this->validate();

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' =>     $this->password ? Hash::make($this->password) : $user->password,
                'role' => $this->role,
            ]);
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'status' => true,
            ]);
        }


        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
