<?php

namespace Modules\Imports\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Collections\Models\Collection;
use Modules\Imports\Traits\HasCell;

/**
 * @mixin Builder
 */
class CollectionItem extends Model
{
    use HasFactory, HasCell;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uploaded_file_path','original_file_name'
    ];

    /**
     * @return BelongsTo
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
