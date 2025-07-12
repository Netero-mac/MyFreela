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
            self::PENDING => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
            self::IN_PROGRESS => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
            self::COMPLETED => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
            self::CANCELED => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
        };
    }
}