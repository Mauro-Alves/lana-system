<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UserManagement;
use App\Livewire\KanbanBoard;
use App\Livewire\Agenda;

// Redireciona a raiz ('/') para a tela de login (se não estiver logado) 
// ou para o dashboard (se já estiver logado)
Route::get('/', function () {
    return redirect()->route('login');
});

// Grupo de rotas autenticadas do sistema
Route::middleware(['auth', 'verified'])->group(function () {
    // Redireciona a rota padrão 'dashboard' ou acessa direto pelo Kanban
    Route::get('/kanban', KanbanBoard::class)->name('kanban.index');
    Route::get('/dashboard', KanbanBoard::class)->name('dashboard');
    
    // Rota da Agenda / Compromissos agendados
    Route::get('/agenda', Agenda::class)->name('agenda.index');

    // Rota de Gestão de Usuários
    Route::get('/users', UserManagement::class)->name('users.index');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';