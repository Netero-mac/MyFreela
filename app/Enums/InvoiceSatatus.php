<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case PENDING = 'Pendente';
    case PAID = 'Pago';
    case OVERDUE = 'Atrasado';
    case CANCELED = 'Cancelada';
}