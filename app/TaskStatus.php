<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDENTE = 'pendente';
    case EM_ANDAMENTO = 'em_andamento';
    case CONCLUIDA = 'concluida';
    case CANCELADA = 'cancelada';

    public function label(): string
    {
        return match ($this) {
            self::PENDENTE      => 'Pendente',
            self::EM_ANDAMENTO => 'Em andamento',
            self::CONCLUIDA     => 'Concluída',
            self::CANCELADA     => 'Cancelada',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
