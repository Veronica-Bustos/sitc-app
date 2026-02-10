<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class MaintenanceRecord extends Model
{
    use \App\Traits\Filterable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'request_date',
        'intervention_date',
        'completion_date',
        'type',
        'status',
        'priority',
        'description',
        'diagnosis',
        'actions_taken',
        'parts_replaced',
        'cost',
        'technician_id',
        'requester_id',
        'next_maintenance_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'item_id' => 'integer',
            'request_date' => 'date',
            'intervention_date' => 'date',
            'completion_date' => 'date',
            'cost' => 'decimal:2',
            'technician_id' => 'integer',
            'requester_id' => 'integer',
            'next_maintenance_date' => 'date',
        ];
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id'])]
    #[SearchUsingFullText(['description', 'diagnosis', 'actions_taken'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'diagnosis' => $this->diagnosis,
            'actions_taken' => $this->actions_taken,
        ];
    }
}
