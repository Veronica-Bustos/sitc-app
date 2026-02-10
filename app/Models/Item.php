<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Item extends Model
{
    use \App\Traits\Filterable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'current_location_id',
        'status',
        'condition',
        'purchase_date',
        'purchase_price',
        'current_value',
        'serial_number',
        'brand',
        'model',
        'supplier',
        'warranty_expiry',
        'barcode',
        'qr_code',
        'minimum_stock',
        'unit_of_measure',
        'weight_kg',
        'dimensions',
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
            'category_id' => 'integer',
            'current_location_id' => 'integer',
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'current_value' => 'decimal:2',
            'warranty_expiry' => 'date',
            'weight_kg' => 'decimal:2',
        ];
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function currentLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id', 'code', 'name'])]
    #[SearchUsingFullText(['name', 'description', 'brand', 'model'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'brand' => $this->brand,
            'model' => $this->model,
        ];
    }
}
