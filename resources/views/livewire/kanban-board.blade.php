<div class="p-6"
     x-data="{
        draggingStageId: null,
        handleDrop(targetStageId) {
            if (!this.draggingStageId || this.draggingStageId === targetStageId) return;

            let container = $el.querySelector('.stages-container');
            let elements = Array.from(container.querySelectorAll('[data-stage-id]'));
            
            let draggedIdx = elements.findIndex(el => parseInt(el.getAttribute('data-stage-id')) === this.draggingStageId);
            let targetIdx = elements.findIndex(el => parseInt(el.getAttribute('data-stage-id')) === targetStageId);

            if (draggedIdx !== -1 && targetIdx !== -1) {
                let draggedEl = elements[draggedIdx];
                let targetEl = elements[targetIdx];

                if (draggedIdx < targetIdx) {
                    targetEl.after(draggedEl);
                } else {
                    targetEl.before(draggedEl);
                }

                let newOrderedIds = Array.from(container.querySelectorAll('[data-stage-id]'))
                                         .map(el => parseInt(el.getAttribute('data-stage-id')));

                $wire.updateStageOrder(newOrderedIds);
            }
            this.draggingStageId = null;
        }
     }">

    <!-- Mensagens de Alerta (Sucesso / Erro) -->
    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
            <strong class="font-bold">Atenção:</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative text-sm" role="alert">
            <strong class="font-bold">Sucesso:</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Barra de Ações Superior (Criar Coluna) -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <h2 class="text-xl font-bold text-gray-800">Quadro Kanban</h2>
        
        <!-- Formulário para Criar Coluna -->
        <form wire:submit.prevent="createStage" class="flex items-center gap-2">
            <input type="text" 
                   wire:model="newStageName" 
                   placeholder="Nome da nova coluna..." 
                   class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 py-1.5 px-3 border" 
                   required />

            <input type="color" 
                   wire:model="newStageColor" 
                   class="w-9 h-9 border-0 p-0 rounded cursor-pointer bg-transparent" 
                   title="Cor da nova coluna" />

            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded-md transition shadow-sm">
                + Nova Coluna
            </button>
        </form>
    </div>

    <!-- Colunas Kanban -->
    <div class="stages-container flex flex-col md:flex-row gap-6 overflow-x-auto pb-4 items-start">
        @foreach($stages as $stage)
            <div wire:key="stage-col-{{ $stage->id }}"
                 data-stage-id="{{ $stage->id }}"
                 draggable="true"
                 x-on:dragstart="draggingStageId = {{ $stage->id }}; $event.dataTransfer.effectAllowed = 'move'"
                 x-on:dragover.prevent
                 x-on:drop="handleDrop({{ $stage->id }})"
                 class="w-full md:w-80 shrink-0 bg-gray-100 rounded-lg p-4 shadow-sm border-t-4 border-gray-200 flex flex-col min-h-[600px] cursor-grab active:cursor-grabbing"
                 style="border-top-color: {{ $stage->color }}">
                
                <!-- Cabeçalho da Etapa -->
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-300 select-none">
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <span class="text-gray-400 cursor-move hover:text-gray-600" title="Arrastar para reordenar coluna">⋮⋮</span>
                        
                        <!-- Seletor de Cores -->
                        <div x-data="{ open: false }" class="relative shrink-0" @click.away="open = false">
                            <button @click="open = !open" 
                                    type="button" 
                                    class="w-4 h-4 rounded border border-gray-300 shadow-sm transition-transform hover:scale-110 flex items-center justify-center" 
                                    style="background-color: {{ $stage->color }}"
                                    title="Alterar cor da coluna">
                            </button>

                            <div x-show="open" 
                                 x-transition 
                                 class="absolute left-0 mt-2 p-2 bg-white rounded-md shadow-xl border border-gray-200 z-50 w-40">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase mb-1">Cores predefinidas</p>
                                
                                <div class="grid grid-cols-5 gap-1.5 mb-2">
                                    @php
                                        $presetColors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#64748b', '#06b6d4', '#84cc16', '#14b8a6'];
                                    @endphp
                                    @foreach($presetColors as $color)
                                        <button type="button" 
                                                wire:click="updateStageColor({{ $stage->id }}, '{{ $color }}')" 
                                                @click="open = false" 
                                                class="w-5 h-5 rounded hover:opacity-80 transition cursor-pointer border border-black/10" 
                                                style="background-color: {{ $color }}">
                                        </button>
                                    @endforeach
                                </div>

                                <div class="border-t border-gray-100 pt-1.5">
                                    <label class="text-[10px] font-semibold text-gray-400 uppercase block mb-1">Cor Customizada</label>
                                    <div class="flex items-center gap-1.5">
                                        <input type="color" 
                                               value="{{ $stage->color }}" 
                                               wire:change="updateStageColor({{ $stage->id }}, $event.target.value)"
                                               @change="open = false"
                                               class="w-6 h-6 border-0 p-0 rounded cursor-pointer bg-transparent" />
                                        <span class="text-xs text-gray-500 uppercase font-mono">{{ $stage->color }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nome da Coluna (Editável) -->
                        <input type="text" 
                               value="{{ $stage->name }}" 
                               wire:blur="updateStageName({{ $stage->id }}, $event.target.value)"
                               wire:keydown.enter="$event.target.blur()"
                               class="font-bold text-base text-gray-700 bg-transparent border-0 focus:ring-1 focus:ring-indigo-500 focus:bg-white rounded px-1 py-0.5 w-full truncate" 
                               title="Clique para editar o nome" />
                    </div>

                    <div class="flex items-center gap-2 shrink-0 ml-2">
                        <span class="text-sm font-semibold text-gray-500 bg-gray-200 px-2.5 py-0.5 rounded-full">
                            {{ $stage->tasks->count() }}
                        </span>

                        <!-- Botão de Excluir Coluna -->
                        <button type="button" 
                                wire:click="deleteStage({{ $stage->id }})" 
                                wire:confirm="Tem certeza que deseja excluir a coluna '{{ $stage->name }}'?"
                                class="text-gray-400 hover:text-red-600 p-1 transition"
                                title="Excluir Coluna">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Lista de Cards -->
                <div class="flex-1 space-y-4 overflow-y-auto">
                    @forelse($stage->tasks as $task)
                        <div wire:key="task-card-{{ $task->id }}" class="bg-white p-4 rounded-md shadow-sm border border-gray-200 hover:shadow-md transition">
                            
                            <!-- Prioridade e Data -->
                            <div class="flex justify-between items-center text-xs mb-2">
                                <select 
                                    wire:change="updateTaskPriority({{ $task->id }}, $event.target.value)"
                                    class="text-xs font-bold py-0.5 px-2 rounded border-0 cursor-pointer focus:ring-1 focus:ring-indigo-500
                                           @if($task->priority === 'high') bg-red-100 text-red-800
                                           @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                           @else bg-green-100 text-green-800 @endif"
                                >
                                    <option value="high" @selected($task->priority === 'high')>Alta</option>
                                    <option value="medium" @selected($task->priority === 'medium')>Média</option>
                                    <option value="low" @selected($task->priority === 'low')>Baixa</option>
                                </select>

                                <span class="text-gray-400">
                                    {{ $task->created_at->format('d/m H:i') }}
                                </span>
                            </div>

                            <!-- Título, Botão de Edição e Resumo Inteligente/Descrição -->
                            <div x-data="{ expanded: false }">
                                <div class="flex items-center justify-between gap-1 mb-1">
                                    <h4 class="font-bold text-gray-800 text-sm leading-snug">{{ $task->title }}</h4>
                                    
                                    <button wire:click="openEditSummaryModal({{ $task->id }})" 
                                            class="text-gray-400 hover:text-indigo-600 p-1 rounded transition shrink-0"
                                            title="Editar resumo ou título">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </div>
                                
                                @if(!empty($task->summary))
                                    <div class="mb-3 bg-indigo-50/70 p-2.5 rounded border border-indigo-100">
                                        <div class="flex items-center gap-1 text-[10px] font-semibold text-indigo-600 uppercase mb-1">
                                            <span>⚡ Resumo Inteligente</span>
                                        </div>
                                        <p @click="expanded = !expanded" 
                                           :class="expanded ? '' : 'line-clamp-3'" 
                                           class="text-gray-700 text-xs cursor-pointer hover:text-gray-900 transition-colors select-none leading-relaxed"
                                           :title="expanded ? 'Clique para recolher' : 'Clique para ler tudo'">
                                            {{ $task->summary }}
                                        </p>
                                    </div>
                                @else
                                    <p @click="expanded = !expanded" 
                                       :class="expanded ? '' : 'line-clamp-3'" 
                                       class="text-gray-600 text-xs mb-3 cursor-pointer hover:text-gray-900 transition-colors select-none leading-relaxed"
                                       :title="expanded ? 'Clique para recolher' : 'Clique para ler tudo'">
                                        {{ $task->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Remetente WhatsApp -->
                            @if($task->message)
                                <div class="bg-gray-50 p-2 rounded text-xs text-gray-500 border border-gray-100 mb-3">
                                    <p class="font-semibold text-gray-700">📱 {{ $task->message->sender_name }}</p>
                                    <p>{{ $task->message->phone_number }}</p>
                                </div>
                            @endif

                            <!-- Responsável -->
                            <div class="text-xs text-gray-500 mb-3 flex items-center justify-between">
                                <span>Responsável:</span>
                                <span class="font-medium text-gray-700">
                                    {{ $task->user ? $task->user->name : 'Ninguém' }}
                                </span>
                            </div>

                            <!-- Ações Rápidas -->
                            <div class="pt-2 border-t border-gray-100 flex justify-between items-center gap-2">
                                <select 
                                    wire:change="moveTask({{ $task->id }}, $event.target.value)" 
                                    class="text-xs border-gray-300 rounded-md py-1 px-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-600"
                                >
                                    <option value="" disabled selected>Mover para...</option>
                                    @foreach($stages as $s)
                                        @if($s->id !== $stage->id)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                @if(!$stage->is_final)
                                    <button 
                                        wire:click="completeTask({{ $task->id }})" 
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-2.5 py-1 rounded transition font-medium shrink-0"
                                    >
                                        Dar Baixa
                                    </button>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-xs italic">
                            Nenhum card nesta etapa.
                        </div>
                    @endforelse
                </div>

            </div>
        @endforeach
    </div>

    <!-- Modal de Edição do Resumo Inteligente -->
    @if($showSummaryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4"
             x-data
             x-on:keydown.escape.window="$wire.closeSummaryModal()">
            
            <div class="bg-white rounded-lg shadow-xl border border-gray-200 w-full max-w-lg overflow-hidden transition-all transform">
                <!-- Cabeçalho da Modal -->
                <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span>✏️ Editar Resumo Inteligente</span>
                    </h3>
                    <button wire:click="closeSummaryModal" 
                            class="text-gray-400 hover:text-gray-600 transition p-1 rounded-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulário da Modal -->
                <form wire:submit.prevent="saveSummary" class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Título da Tarefa</label>
                        <input type="text" 
                               wire:model="editingTitle" 
                               class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border p-2" 
                               required />
                        @error('editingTitle') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Resumo Inteligente</label>
                        <textarea wire:model="editingSummary" 
                                  rows="5" 
                                  class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border p-2 leading-relaxed"
                                  placeholder="Digite ou edite o resumo inteligente do card..."
                                  required></textarea>
                        @error('editingSummary') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rodapé da Modal -->
                    <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" 
                                wire:click="closeSummaryModal" 
                                class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-md transition border border-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition shadow-sm">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>