<?php

namespace App\Livewire;

use App\Models\Task;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Agenda extends Component
{
    public string $viewMode = 'week'; // 'week', 'calendar' ou 'list'
    public string $filterFilter = 'all'; // 'all', 'today', 'week', 'overdue'

    // Data de referência selecionada
    public string $selectedDate;

    public function mount(): void
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function previousPeriod(): void
    {
        $date = Carbon::parse($this->selectedDate);

        if ($this->viewMode === 'calendar') {
            $this->selectedDate = $date->subMonth()->format('Y-m-d');
        } else {
            $this->selectedDate = $date->subWeek()->format('Y-m-d');
        }
    }

    public function nextPeriod(): void
    {
        $date = Carbon::parse($this->selectedDate);

        if ($this->viewMode === 'calendar') {
            $this->selectedDate = $date->addMonth()->format('Y-m-d');
        } else {
            $this->selectedDate = $date->addWeek()->format('Y-m-d');
        }
    }

    public function goToToday(): void
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $currentDate = Carbon::parse($this->selectedDate);

        if ($this->viewMode === 'calendar') {
            // MÊS: calcula do primeiro ao último dia visível do grid mensal
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth   = $currentDate->copy()->endOfMonth();

            $gridStart = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
            $gridEnd   = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

            $tasks = Task::query()
                ->with(['stage', 'user'])
                ->whereNotNull('due_date')
                ->whereBetween('due_date', [$gridStart->copy()->startOfDay(), $gridEnd->copy()->endOfDay()])
                ->orderBy('due_date', 'asc')
                ->get()
                ->groupBy(fn ($task) => $task->due_date->format('Y-m-d'));

        } elseif ($this->viewMode === 'week') {
            // SEMANA: calcula do domingo ao sábado da semana atual
            $gridStart = $currentDate->copy()->startOfWeek(Carbon::SUNDAY);
            $gridEnd   = $currentDate->copy()->endOfWeek(Carbon::SATURDAY);

            $tasks = Task::query()
                ->with(['stage', 'user'])
                ->whereNotNull('due_date')
                ->whereBetween('due_date', [$gridStart->copy()->startOfDay(), $gridEnd->copy()->endOfDay()])
                ->orderBy('due_date', 'asc')
                ->get()
                ->groupBy(fn ($task) => $task->due_date->format('Y-m-d'));

        } else {
            // LISTA: aplica os filtros selecionados
            $query = Task::query()
                ->with(['stage', 'user'])
                ->whereNotNull('due_date');

            if ($this->filterFilter === 'today') {
                $query->whereDate('due_date', now()->today());
            } elseif ($this->filterFilter === 'week') {
                $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($this->filterFilter === 'overdue') {
                $query->where('due_date', '<', now());
            }

            $tasks = $query->orderBy('due_date', 'asc')->get();
        }

        $stats = [
            'today'   => Task::whereNotNull('due_date')->whereDate('due_date', now()->today())->count(),
            'week'    => Task::whereNotNull('due_date')->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'overdue' => Task::whereNotNull('due_date')->where('due_date', '<', now())->count(),
        ];

        return view('livewire.agenda', [
            'tasks'       => $tasks,
            'stats'       => $stats,
            'currentDate' => $currentDate,
        ]);
    }
}