<?php

namespace App\Enums;

trait HasColors
{
    public function getChartColor(): string
    {
        return match ($this) {
            self::PENDING => '#f97316',
            self::IN_PROGRESS => '#3b82f6',
            self::COMPLETED => '#22c55e',
            self::CANCELED => '#ef4444',
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