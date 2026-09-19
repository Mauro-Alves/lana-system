<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_message_id',
        'user_id',
        'stage_id',
        'title',
        'description',
        'summary',
        'priority',
        'due_date',
        'completed_at',
        'last_replied_at',
    ];

    protected $casts = [
        'due_date'        => 'datetime',
        'completed_at'    => 'datetime',
        'last_replied_at' => 'datetime',
    ];

    /**
     * Mensagem do WhatsApp vinculada a esta tarefa.
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(WhatsappMessage::class, 'whatsapp_message_id');
    }

    /**
     * Usuário responsável pela tarefa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Etapa/coluna do Kanban a qual a tarefa pertence.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Histórico de alterações e movimentações do card.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(TaskLog::class);
    }
}