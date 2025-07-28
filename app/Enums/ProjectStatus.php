<?php

namespace App\Enums;

trait HasColors
{
    /**
     * Retorna a cor em formato hexadecimal para uso em gráficos.
     */
    public function getChartColor(): string
    {
        return match ($this) {
            self::PENDING => '#f97316',
            self::IN_PROGRESS => '#3b82f6',
            self::COMPLETED => '#22c55e',
            self::CANCELED => '#ef4444',
        };
    }

    /**
     * Retorna as classes de CSS para estilização do status.
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::IN_PROGRESS => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::COMPLETED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::CANCELED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        };
    }
}

enum ProjectStatus: string
{
    use HasColors;

    case PENDING = 'Pendente';
    case IN_PROGRESS = 'Em Andamento';
    case COMPLETED = 'Concluído';
    case CANCELED = 'Cancelado';

    /**
     * Verifica se é possível transitar para um novo estado.
     *
     * @param ProjectStatus $newState O novo estado desejado.
     * @return bool
     */
    public function canTransitionTo(ProjectStatus $newState): bool
    {
        // Um estado não pode transitar para si mesmo.
        if ($this === $newState) {
            return false;
        }

        // Define as transições permitidas para cada estado.
        $allowedTransitions = match ($this) {
            self::PENDING => [self::IN_PROGRESS, self::CANCELED],
            self::IN_PROGRESS => [self::COMPLETED, self::CANCELED],
            // Estados terminais: não podem transitar para outros estados.
            self::COMPLETED, self::CANCELED => [],
        };

        return in_array($newState, $allowedTransitions);
    }

    /**
     * Retorna uma lista de transições válidas a partir do estado atual.
     *
     * @return array<int, ProjectStatus>
     */
    public function getAllowedTransitions(): array
    {
        return match ($this) {
            self::PENDING => [self::IN_PROGRESS, self::CANCELED],
            self::IN_PROGRESS => [self::COMPLETED, self::CANCELED],
            self::COMPLETED, self::CANCELED => [],
        };
    }
}