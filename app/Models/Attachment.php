<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Attachment extends Model
{
    use Filterable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_path',
        'file_name',
        'original_name',
        'mime_type',
        'size',
        'disk',
        'description',
        'is_featured',
        'order',
        'uploader_id',
        'attachable_id',
        'attachable_type',
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
            'size' => 'integer',
            'is_featured' => 'boolean',
            'uploader_id' => 'integer',
        ];
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    #[SearchUsingPrefix(['id', 'file_name'])]
    #[SearchUsingFullText(['original_name', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'original_name' => $this->original_name,
            'description' => $this->description,
        ];
    }
}
