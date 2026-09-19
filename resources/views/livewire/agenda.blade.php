<div class="p-6 max-w-7xl mx-auto space-y-6">

    <!-- Header & Controles -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <span>📅</span> Agenda de Atendimentos & Compromissos
            </h1>
            <p class="text-xs text-gray-500 mt-1">Acompanhe as solicitações com data/horário agendado pelos clientes.</p>
        </div>

        <!-- Seletor de Visão -->
        <div class="flex items-center gap-3">
            <div class="bg-gray-100 p-1 rounded-lg flex text-xs font-semibold">
                <button wire:click="$set('viewMode', 'week')" 
                        class="px-3 py-1.5 rounded-md transition {{ $viewMode === 'week' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Semanal
                </button>
                <button wire:click="$set('viewMode', 'calendar')" 
                        class="px-3 py-1.5 rounded-md transition {{ $viewMode === 'calendar' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Mensal
                </button>
                <button wire:click="$set('viewMode', 'list')" 
                        class="px-3 py-1.5 rounded-md transition {{ $viewMode === 'list' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Lista por Prazos
                </button>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo Rápido -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div wire:click="$set('filterFilter', 'today'); $set('viewMode', 'list')" 
             class="cursor-pointer bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-400 transition shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase">Para Hoje</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $stats['today'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold">🎯</div>
        </div>

        <div wire:click="$set('filterFilter', 'week'); $set('viewMode', 'list')" 
             class="cursor-pointer bg-white p-4 rounded-lg border border-gray-200 hover:border-indigo-400 transition shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase">Esta Semana</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $stats['week'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">📆</div>
        </div>

        <div wire:click="$set('filterFilter', 'overdue'); $set('viewMode', 'list')" 
             class="cursor-pointer bg-white p-4 rounded-lg border border-gray-200 hover:border-red-400 transition shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase">Atrasados / Pendentes</p>
                <p class="text-2xl font-extrabold text-red-600 mt-1">{{ $stats['overdue'] }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center font-bold">⚠️</div>
        </div>
    </div>

    @if($viewMode === 'week')
        <!-- VISÃO SEMANAL -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <button wire:click="previousPeriod" class="p-1.5 rounded-lg border hover:bg-gray-50 text-gray-600 text-xs">◀ Semana Anterior</button>
                    <button wire:click="goToToday" class="px-3 py-1.5 rounded-lg border hover:bg-gray-50 text-gray-700 font-semibold text-xs">Hoje</button>
                    <button wire:click="nextPeriod" class="p-1.5 rounded-lg border hover:bg-gray-50 text-gray-600 text-xs">Próxima Semana ▶</button>
                </div>
                <h2 class="text-base font-bold text-gray-800 capitalize">
                    Semana de {{ $currentDate->copy()->startOfWeek(\Carbon\Carbon::SUNDAY)->translatedFormat('d/m') }} a {{ $currentDate->copy()->endOfWeek(\Carbon\Carbon::SATURDAY)->translatedFormat('d/m/Y') }}
                </h2>
            </div>

            @php
                $startOfWeek = $currentDate->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                $endOfWeek   = $currentDate->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                $weekDays    = \Carbon\CarbonPeriod::create($startOfWeek, $endOfWeek);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-7 divide-y md:divide-y-0 md:divide-x divide-gray-200 min-h-[350px]">
                @foreach($weekDays as $day)
                    @php
                        $formattedDate = $day->format('Y-m-d');
                        $dayTasks = $tasks->get($formattedDate, collect());
                        $isToday = $day->isToday();
                    @endphp

                    <div class="p-2.5 flex flex-col justify-start {{ $isToday ? 'bg-indigo-50/40' : 'bg-white' }}">
                        <div class="text-center pb-2 mb-2 border-b border-gray-100 flex md:flex-col items-center justify-between md:justify-center gap-1">
                            <span class="text-xs font-semibold text-gray-500 uppercase">{{ $day->translatedFormat('ddd') }}</span>
                            <span class="text-sm font-extrabold px-2 py-0.5 rounded-md {{ $isToday ? 'bg-indigo-600 text-white' : 'text-gray-800' }}">
                                {{ $day->format('d/m') }}
                            </span>
                        </div>

                        <div class="space-y-2 overflow-y-auto flex-1">
                            @forelse($dayTasks as $task)
                                <div class="p-2 rounded-lg border text-xs shadow-2xs transition hover:shadow-xs {{ $task->due_date->isPast() && !$day->isToday() ? 'bg-red-50 border-red-200 text-red-900' : 'bg-slate-50 border-slate-200 text-slate-800' }}">
                                    <div class="font-bold flex items-center justify-between text-indigo-700">
                                        <span>⏰ {{ $task->due_date->format('H:i') }}</span>
                                    </div>
                                    <div class="font-semibold mt-1 text-gray-900 leading-snug">
                                        {{ $task->title }}
                                    </div>
                                    <div class="mt-2 pt-1 border-t border-gray-200/60 flex items-center justify-between text-[10px] text-gray-500">
                                        <span class="px-1.5 py-0.5 rounded bg-white border border-gray-200 font-medium">{{ $task->stage?->name ?? 'N/A' }}</span>
                                        <span>{{ $task->user?->name ?? 'Pendente' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-300 text-[11px] italic">Sem agendamentos</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($viewMode === 'calendar')
        <!-- VISÃO MENSAL -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <button wire:click="previousPeriod" class="p-1.5 rounded-lg border hover:bg-gray-50 text-gray-600 text-xs">◀ Mês Anterior</button>
                    <button wire:click="goToToday" class="px-3 py-1.5 rounded-lg border hover:bg-gray-50 text-gray-700 font-semibold text-xs">Hoje</button>
                    <button wire:click="nextPeriod" class="p-1.5 rounded-lg border hover:bg-gray-50 text-gray-600 text-xs">Próximo Mês ▶</button>
                </div>
                <h2 class="text-base font-bold text-gray-800 capitalize">
                    {{ $currentDate->translatedFormat('F \d\e Y') }}
                </h2>
            </div>

            <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50 text-center text-xs font-bold text-gray-600 py-2">
                <div>Dom</div><div>Seg</div><div>Ter</div><div>Qua</div><div>Qui</div><div>Sex</div><div>Sáb</div>
            </div>

            @php
                $startDay = $currentDate->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
                $endDay   = $currentDate->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
                $period   = \Carbon\CarbonPeriod::create($startDay, $endDay);
            @endphp

            <div class="grid grid-cols-7 divide-x divide-y divide-gray-100">
                @foreach($period as $day)
                    @php
                        $formattedDate = $day->format('Y-m-d');
                        $dayTasks = $tasks->get($formattedDate, collect());
                        $isCurrentMonth = $day->month === $currentDate->month;
                        $isToday = $day->isToday();
                    @endphp

                    <div class="min-h-[110px] p-1.5 flex flex-col justify-between {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50/60 text-gray-400' }} {{ $isToday ? 'bg-indigo-50/30' : '' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold px-1.5 py-0.5 rounded-md {{ $isToday ? 'bg-indigo-600 text-white' : 'text-gray-700' }}">
                                {{ $day->day }}
                            </span>
                            @if($dayTasks->count() > 0)
                                <span class="text-[10px] text-indigo-600 font-bold bg-indigo-50 px-1 rounded">{{ $dayTasks->count() }}</span>
                            @endif
                        </div>

                        <div class="space-y-1 overflow-y-auto max-h-[85px] pr-0.5">
                            @foreach($dayTasks as $task)
                                <div class="p-1.5 rounded border text-[11px] leading-tight shadow-2xs {{ $task->due_date->isPast() && !$day->isToday() ? 'bg-red-50 border-red-200 text-red-800' : 'bg-slate-50 border-slate-200 text-slate-800' }}">
                                    <div class="font-semibold truncate" title="{{ $task->title }}">
                                        {{ $task->due_date->format('H:i') }} - {{ $task->title }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($viewMode === 'list')
        <!-- VISÃO DE LISTA DETALHADA -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 text-sm">Lista de Demandas Agendadas</h3>
                <div class="flex gap-2 text-xs">
                    <button wire:click="$set('filterFilter', 'all')" class="px-2.5 py-1 rounded {{ $filterFilter === 'all' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">Todas</button>
                    <button wire:click="$set('filterFilter', 'today')" class="px-2.5 py-1 rounded {{ $filterFilter === 'today' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">Hoje</button>
                    <button wire:click="$set('filterFilter', 'week')" class="px-2.5 py-1 rounded {{ $filterFilter === 'week' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600' }}">Esta Semana</button>
                    <button wire:click="$set('filterFilter', 'overdue')" class="px-2.5 py-1 rounded {{ $filterFilter === 'overdue' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600' }}">Atrasadas</button>
                </div>
            </div>

            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Data / Horário Marcado</th>
                        <th class="px-4 py-3">Tarefa / Solicitação</th>
                        <th class="px-4 py-3">Etapa Atual</th>
                        <th class="px-4 py-3">Responsável</th>
                        <th class="px-4 py-3 text-right">Status da Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 font-bold text-gray-800 whitespace-nowrap">
                                📅 {{ $task->due_date->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $task->title }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border" style="background-color: {{ $task->stage?->color ?? '#f3f4f6' }}20; color: {{ $task->stage?->color ?? '#374151' }}; border-color: {{ $task->stage?->color ?? '#d1d5db' }}">
                                    {{ $task->stage?->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $task->user?->name ?? 'Sem responsável' }}
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                @if($task->due_date->isPast())
                                    <span class="px-2 py-1 rounded bg-red-100 text-red-800 font-bold text-[10px]">Atrasado / Pendente</span>
                                @elseif($task->due_date->isToday())
                                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 font-bold text-[10px]">Hoje</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-800 font-bold text-[10px]">No Prazo</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 italic">
                                Nenhuma tarefa encontrada para este filtro.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>