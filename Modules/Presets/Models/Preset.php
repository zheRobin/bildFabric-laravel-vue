<?php

namespace Modules\Presets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Collections\Models\Collection;
use Modules\Presets\Traits\HasLanguage;
use Modules\Presets\Traits\HasOpenAIParams;

class Preset extends Model
{
    use HasFactory, HasLanguage, HasOpenAIParams;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'model',
        'system_prompt',
        'user_prompt',
        'collection_id',
        'temperature',
        'top_p',
        'frequency_penalty',
        'presence_penalty',
        'n',
        'max_tokens',
        'stop',
        'logit_bias',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stop' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}