<div class="p-6 max-w-7xl mx-auto space-y-6">

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-md text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-md text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulário de Cadastro -->
    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Cadastrar Novo Usuário</h2>
        
        <form wire:submit.prevent="createUser" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nome Completo</label>
                <input type="text" 
                       wire:model="name" 
                       placeholder="Ex: Maria Silva"
                       class="w-full text-sm border-gray-300 rounded-md border p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" />
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">E-mail</label>
                <input type="email" 
                       wire:model="email" 
                       placeholder="maria@empresa.com"
                       class="w-full text-sm border-gray-300 rounded-md border p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror" />
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Senha</label>
                <input type="password" 
                       wire:model="password" 
                       placeholder="••••••••"
                       class="w-full text-sm border-gray-300 rounded-md border p-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror" />
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-3 flex justify-end">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2.5 rounded-md transition shadow-sm">
                    + Cadastrar Usuário
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela e Busca -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
            <h3 class="font-bold text-gray-800 text-base">Usuários Cadastrados</h3>
            
            <div class="w-full sm:w-64">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar por nome ou e-mail..."
                       class="w-full text-xs border-gray-300 rounded-md border p-2 focus:ring-indigo-500 focus:border-indigo-500" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">E-mail</th>
                        <th class="px-4 py-3">Data de Cadastro</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr wire:key="user-row-{{ $user->id }}" class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 font-mono text-gray-400">#{{ $user->id }}</td>
                            <td class="px-4 py-3 font-bold text-gray-800">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-gray-400">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                @if(auth()->id() !== $user->id)
                                    <button type="button" 
                                            wire:click="deleteUser({{ $user->id }})" 
                                            wire:confirm="Tem certeza que deseja remover o usuário {{ $user->name }}?"
                                            class="text-red-500 hover:text-red-700 font-semibold transition"
                                            title="Excluir Usuário">
                                        Excluir
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">Você</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 italic">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>