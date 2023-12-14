<?php

namespace Modules\Collections\Models;

use App\Models\Compilations;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Export\Models\Export;
use Modules\Imports\Models\CollectionItem;
use Modules\Imports\Traits\HasHeaders;
use Modules\Imports\Traits\HasImport;
use Modules\Presets\Models\Preset;

/**
 * @mixin Builder
 */
class Collection extends Model
{
    use HasFactory, HasImport, HasHeaders;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'team_id',
        'last_uploaded_file_path',
    ];
    /**
     * Purge all of the collection's resources.
     *
     * @return void
     */
    public function purge()
    {
        $this->delete();
    }

    /**
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(CollectionItem::class);
    }

    /**
     * @return HasMany
     */
    public function presets(): HasMany
    {
        return $this->hasMany(Preset::class);
    }

    /**
     * @return HasMany
     */
    public function compilations(): HasMany
    {
        return $this->hasMany(Compilations::class);
    }

    /**
     * @return HasMany
     */
    public function exports(): HasMany
    {
        return $this->hasMany(Export::class);
    }
}
