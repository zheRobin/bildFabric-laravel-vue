<?php

namespace Modules\Imports\Contracts;

use App\Models\User;
use Modules\Collections\Models\Collection;

interface Importer
{
    public function import(User $user, Collection $collection): void;
}
