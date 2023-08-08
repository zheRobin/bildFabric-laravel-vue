<?php

namespace Modules\Export\Jobs;

use App\Models\Compilations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Export\Models\CompilationExport;
use Modules\Fortify\Mail\RegistrationEmail;
use Modules\OpenAI\Contracts\BuildsParams;
use Modules\Translations\Models\Language;
use Modules\Presets\Models\Preset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\User;
use Modules\Export\Events\GetNotificationWhenQueueEndEvents;

class GenerateExports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $compilationId;
    protected $items;
    protected $user;
    protected $team_id;
    public function __construct($compilationId, $items, $idUser, $team_id)
    {
        $this->compilationId = $compilationId;
        $this->items = $items;
        $this->user = User::where('id', $idUser)->get()->first();
        $this->team_id = $team_id;
    }

    public function handle(Preset $preset): void
    {
        $compilationModel = Compilations::where('id', $this->compilationId)->first();

        $compilationName = $compilationModel->name;
        $presetIds = $compilationModel->preset_ids;

        $builder = app(BuildsParams::class);
        $result = [];

        foreach ($presetIds as $id) {
            $pres = $preset->where('id', $id)->first();
            $lang = Language::get()->where('id', $pres->output_language_id ?? 31)->first()->code;
            $result[$compilationName . '_' . $pres->name] = [];
            foreach ($this->items as $index => $item) {
                $params = $builder->build($this->user, $pres, $item);
                $response = OpenAI::chat()->create($params);
                $content = $response->choices[0]->message->content;
                $result[$compilationName . '_' . $pres->name]['def'][$index] = $content;
            }
        }

        $now = Carbon::now();
        $exports = new CompilationExport();
        $fullDate = $now->format('Y-m-d H:i:s');

        $resultData[$fullDate] = [];
        foreach ($result as $name => $value) {
            $resultData[$fullDate][$name] = $value;
        }

        $currentDateTime = Carbon::now();

        $exports->compilation_id = $this->compilationId;
        $exports->team_id = $this->team_id;
        $exports->data = $resultData[$fullDate];
        $exports->name = $compilationName . '_' . $currentDateTime->format('Y-m-d H:i:s');;
        $exports->save();

        event(new GetNotificationWhenQueueEndEvents('Черга закінчена!'));
    }
}