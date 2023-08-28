<?php

namespace Modules\Export\Jobs;

use App\Models\Compilations;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Modules\Export\Models\Export;
use Modules\Imports\Models\CollectionItem;
use Modules\OpenAI\Contracts\BuildsParams;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class ProcessExportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,
        protected Compilations $compilation,
        protected CollectionItem $collectionItem,
        protected Export $export,
    ) {
    }

    /**
     * @return array
     */
    public function middleware(): array
    {
        return [new SkipIfBatchCancelled];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $builder = app(BuildsParams::class);

        $completions = [];
        foreach ($this->compilation->getPresets() as $preset) {
            $params = $builder->build($this->user, $preset, $this->collectionItem);
            $response = OpenAI::chat()->create($params);

            $header = Str::slug($this->compilation->name) . '_' . Str::slug($preset->name);
            $completions[] = [
                'header' => $header,
                'value' => $this->formatResponse($response),
            ];
        }

        $this->export->items()->create([
            'data' => $this->collectionItem->getCells(),
            'completions' => $completions,
        ]);
    }

    /**
     * @param CreateResponse $response
     * @return mixed|null
     */
    protected function formatResponse(CreateResponse $response): mixed
    {
        $firstChoice = $response->choices[0]->toArray();

        return $firstChoice['message']['content'] ?? null;
    }
}