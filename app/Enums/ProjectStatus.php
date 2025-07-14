<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case PENDING = 'Pendente';
    case IN_PROGRESS = 'Em Andamento';
    case COMPLETED = 'Concluído';
    case CANCELED = 'Cancelado';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::IN_PROGRESS => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::COMPLETED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::CANCELED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        };
    }

     public function getChartColor(): string
    {
        return match ($this) {
            self::PENDING => 'rgba(251, 191, 36, 0.7)',     // Amarelo
            self::IN_PROGRESS => 'rgba(59, 130, 246, 0.7)', // Azul
            self::COMPLETED => 'rgba(16, 185, 129, 0.7)',   // Verde
            self::CANCELED => 'rgba(239, 68, 68, 0.7)',    // Vermelho
        };
    }
}

