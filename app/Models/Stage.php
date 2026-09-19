<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'order',
        'is_final',
    ];

    protected $casts = [
        'order'    => 'integer',
        'is_final' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stage) {
            if (empty($stage->slug)) {
                $stage->slug = Str::slug($stage->name);
            }
        });
    }

    /**
     * Uma etapa possui várias tarefas (cards).
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}