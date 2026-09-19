<?php

namespace App\Livewire;

use App\Models\Stage;
use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class KanbanBoard extends Component
{
    // Propriedades para criação de nova coluna
    public string $newStageName = '';
    public string $newStageColor = '#3b82f6';

    // Propriedades para modal de edição do resumo inteligente da Task
    public bool $showSummaryModal = false;
    public ?int $editingTaskId = null;
    public string $editingTitle = '';
    public string $editingSummary = '';

    protected array $rules = [
        'newStageName'  => 'required|string|max:255',
        'newStageColor' => 'required|string',
    ];

    /**
     * Cria uma nova coluna/etapa no Kanban.
     */
    public function createStage(): void
    {
        $this->validate();

        $maxOrder = Stage::max('order') ?? 0;

        Stage::create([
            'name'     => $this->newStageName,
            'slug'     => Str::slug($this->newStageName),
            'color'    => $this->newStageColor,
            'order'    => $maxOrder + 1,
            'is_final' => false,
        ]);

        $this->reset(['newStageName']);
        $this->newStageColor = '#3b82f6';
    }

    /**
     * Renomeia uma coluna existente.
     */
    public function updateStageName(int $stageId, string $name): void
    {
        $name = trim($name);
        if (!empty($name)) {
            Stage::where('id', $stageId)->update([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }

    /**
     * Exclui uma coluna (somente se não houver tarefas atreladas).
     */
    public function deleteStage(int $stageId): void
    {
        $stage = Stage::withCount('tasks')->findOrFail($stageId);

        if ($stage->tasks_count > 0) {
            session()->flash('error', "Não é possível excluir a coluna '{$stage->name}' pois ela contém cards. Mova ou exclua os cards primeiro.");
            return;
        }

        $stage->delete();
    }

    /**
     * Atualiza a cor de identificação da coluna.
     */
    public function updateStageColor(int $stageId, string $color): void
    {
        Stage::where('id', $stageId)->update(['color' => $color]);
    }

    /**
     * Reordena as colunas do Kanban via Drag & Drop.
     */
    public function updateStageOrder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Stage::where('id', $id)->update([
                'order' => $index + 1,
            ]);
        }
    }

    /**
     * Atualiza a prioridade do Card (high, medium, low).
     */
    public function updateTaskPriority(int $taskId, string $priority): void
    {
        $task = Task::findOrFail($taskId);
        $oldPriority = $task->priority;

        $task->update(['priority' => $priority]);

        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action'  => "Alterou a prioridade de '{$oldPriority}' para '{$priority}'",
        ]);
    }

    /**
     * Move um Card de uma coluna para outra.
     */
    public function moveTask(int $taskId, int $newStageId): void
    {
        $task = Task::findOrFail($taskId);
        $oldStageName = $task->stage->name ?? 'Desconhecido';
        $newStage = Stage::findOrFail($newStageId);

        $task->update([
            'stage_id' => $newStage->id,
        ]);

        if ($newStage->is_final && !$task->completed_at) {
            $task->update(['completed_at' => now()]);
        }

        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action'  => "Moveu da etapa '{$oldStageName}' para '{$newStage->name}'",
        ]);
    }

    /**
     * Conclui a Task movendo para a etapa marcada como final.
     */
    public function completeTask(int $taskId): void
    {
        $finalStage = Stage::where('is_final', true)->first();

        if ($finalStage) {
            $this->moveTask($taskId, $finalStage->id);
        }
    }

    /**
     * Abre a modal para edição do título e resumo gerado pela IA.
     */
    public function openEditSummaryModal(int $taskId): void
    {
        $task = Task::findOrFail($taskId);

        $this->editingTaskId   = $task->id;
        $this->editingTitle    = $task->title;
        $this->editingSummary  = $task->summary ?? $task->description ?? '';
        $this->showSummaryModal = true;
    }

    /**
     * Salva as alterações feitas manualmente no resumo/título do Card.
     */
    public function saveSummary(): void
    {
        $this->validate([
            'editingTitle'   => 'required|string|max:255',
            'editingSummary' => 'required|string|max:2000',
        ]);

        if ($this->editingTaskId) {
            $task = Task::findOrFail($this->editingTaskId);

            $task->update([
                'title'   => $this->editingTitle,
                'summary' => $this->editingSummary,
            ]);

            TaskLog::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action'  => 'Editou manualmente o título/resumo do card.',
            ]);

            $this->closeSummaryModal();
            session()->flash('success', 'Resumo atualizado com sucesso!');
        }
    }

    /**
     * Fecha a modal de edição de resumo.
     */
    public function closeSummaryModal(): void
    {
        $this->showSummaryModal = false;
        $this->reset(['editingTaskId', 'editingTitle', 'editingSummary']);
    }

    public function render()
    {
        $stages = Stage::orderBy('order', 'asc')
            ->with(['tasks' => function ($query) {
                $query->with(['message', 'user'])
                      ->orderBy('updated_at', 'desc');
            }])
            ->get();

        return view('livewire.kanban-board', [
            'stages' => $stages,
        ]);
    }
}