<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class UserManagement extends Component
{
    use WithPagination;

    // Campos do formulário
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $search = '';

    // Regras de validação
    protected function rules(): array
    {
        return [
            'name'     => 'required|string|min:3|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }

    protected $messages = [
        'name.required'     => 'O nome é obrigatório.',
        'email.required'    => 'O e-mail é obrigatório.',
        'email.email'       => 'Informe um e-mail válido.',
        'email.unique'      => 'Este e-mail já está cadastrado.',
        'password.required' => 'A senha é obrigatória.',
        'password.min'      => 'A senha deve ter pelo menos 6 caracteres.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createUser(): void
    {
        $validatedData = $this->validate();

        User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $this->reset(['name', 'email', 'password']);
        session()->flash('success', 'Usuário cadastrado com sucesso!');
    }

    public function deleteUser(int $userId): void
    {
        // Trava de segurança: impede excluir a si próprio
        if (auth()->id() === $userId) {
            session()->flash('error', 'Você não pode excluir sua própria conta.');
            return;
        }

        $user = User::findOrFail($userId);
        $user->delete();

        session()->flash('success', 'Usuário removido com sucesso!');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.user-management', [
            'users' => $users,
        ]);
    }
}