<?php

namespace App\Console\Commands;

use App\Models\User; 
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use NeteroMac\MeuFreela\Models\Client;
use NeteroMac\MeuFreela\Models\Project;

class LimparDadosCommand extends Command
{
    /**
     * A assinatura do comando no console.
     * Adicionamos o argumento opcional {user?}
     *
     * @var string
     */
    protected $signature = 'data:clear 
                            {user? : O ID do usuário para o qual os dados serão apagados}
                            {--m|model=all : O que deve ser apagado (all, projects, clients)}';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Apaga projetos e/ou clientes. Pode ser filtrado por usuário. Use com CUIDADO!';

    /**
     * Executa a lógica do comando.
     */
    public function handle(): int
    {
        $model = $this->option('model');
        $userId = $this->argument('user');
        $user = null;

        // Valida a opção --model
        if (!in_array($model, ['all', 'projects', 'clients'])) {
            $this->error('Opção --model inválida! Use "all", "projects" ou "clients".');
            return Command::FAILURE;
        }

        // Se um ID de usuário foi fornecido, busca o usuário
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuário com ID {$userId} não encontrado.");
                return Command::FAILURE;
            }
            $confirmationMessage = "Você tem CERTEZA que deseja apagar os dados ({$model}) do usuário '{$user->name}' (ID: {$user->id})? Esta ação não pode ser desfeita.";
        } else {
            $confirmationMessage = "Você tem CERTEZA que deseja apagar TODOS os dados ({$model}) de TODOS os usuários? Esta ação não pode ser desfeita.";
        }

        // Pede confirmação ao usuário
        if (!$this->confirm($confirmationMessage)) {
            $this->info('Operação cancelada.');
            return Command::SUCCESS;
        }
        
        $this->warn('Iniciando a limpeza de dados...');

        try {
            DB::transaction(function () use ($model, $user) {
                // Se a opção for para apagar clientes ou tudo
                if ($model === 'all' || $model === 'clients') {
                    $this->clearClients($user);
                }
                
                // Se for para apagar apenas projetos
                if ($model === 'projects') {
                    $this->clearProjects($user);
                }
            });
        } catch (\Exception $e) {
            $this->error('Ocorreu um erro durante a limpeza: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->info('Limpeza de dados concluída com sucesso!');
        return Command::SUCCESS;
    }

    /**
     * Lógica para apagar os clientes, com um usuário opcional.
     */
    private function clearClients(?User $user): void
    {
        // Se um usuário foi passado, constrói a query a partir do relacionamento
        // Se não, usa o modelo diretamente para apagar todos.
        $query = $user ? $user->clients() : Client::query();
        
        $count = $query->count();

        if ($count > 0) {
            $query->delete(); // Usamos delete() em vez de truncate() para queries com 'where'
            $target = $user ? "do usuário {$user->name}" : "de todos os usuários";
            $this->line("<fg=yellow>{$count} cliente(s) apagado(s) {$target}.</>");
            $this->line("<fg=gray>Projetos associados também foram apagados via cascade.</>");
        } else {
            $this->line('Nenhum cliente encontrado para apagar.');
        }
    }

    /**
     * Lógica para apagar os projetos, com um usuário opcional.
     */
    private function clearProjects(?User $user): void
    {
        $query = $user ? $user->projects() : Project::query();
        
        $count = $query->count();

        if ($count > 0) {
            $query->delete();
            $target = $user ? "do usuário {$user->name}" : "de todos os usuários";
            $this->line("<fg=yellow>{$count} projeto(s) apagado(s) {$target}.</>");
        } else {
            $this->line('Nenhum projeto encontrado para apagar.');
        }
    }
}