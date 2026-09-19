<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskLog extends Model
{
    use HasFactory;

    /**
     * Atributos permitidos para atribuição em massa.
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'action',
    ];

    // Se preferir liberar todos os campos de uma vez, pode usar:
    // protected $guarded = [];

    /**
     * Relacionamento com a Task.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Relacionamento com o Usuário que fez a ação.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}