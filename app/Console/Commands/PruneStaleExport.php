<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Export\Models\Export;
use Modules\Export\Models\JobBatch;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'export:prune-batches')]
class PruneStaleExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:prune-batches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune stale job-batch entries from the exports table';

    /**
     * The ```job_batch_id``` in ```exports``` table that no longer references any record in the ```jobs``` table.
     *
     * @var array<string>
     */
    private array $prunableBatchIds = [];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Export::select('id', 'job_batch_id')
            ->with('batch')
            ->whereNotNull('job_batch_id')
            ->get()
            ->map(fn(Export $export) => $export->batch->id)
            ->each(function ($batchId) {
                $jobs = DB::table('jobs')->where('payload', 'LIKE', "%{$batchId}%");

                if (!$jobs->exists()) {
                    $this->prunableBatchIds[] = $batchId;
                }
            });

        $this->prunableBatchIds = array_unique($this->prunableBatchIds);

        Export::whereIn('job_batch_id', $this->prunableBatchIds)
            ->update(['job_batch_id' => null]);
    }
}
