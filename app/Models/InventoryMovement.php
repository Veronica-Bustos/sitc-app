<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class InventoryMovement extends Model
{
    use \App\Traits\Filterable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'from_location_id',
        'to_location_id',
        'movement_type',
        'user_id',
        'quantity',
        'notes',
        'reason',
        'reference_document',
        'performed_at',
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
            'from_location_id' => 'integer',
            'to_location_id' => 'integer',
            'user_id' => 'integer',
            'performed_at' => 'datetime',
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

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function user(): BelongsTo
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
    #[SearchUsingFullText(['reason', 'notes'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'reason' => $this->reason,
            'notes' => $this->notes,
        ];
    }
}
