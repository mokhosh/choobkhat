<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    #[\Override]
    protected static function booted()
    {
        static::saved(function ($project): void {
            if ($project->default) {
                static::query()->whereKeyNot($project->id)->update(['default' => false]);
            }
        });
    }

    public static function default(): ?static
    {
        return Project::query()->latest('updated_at')->where('default', true)->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
