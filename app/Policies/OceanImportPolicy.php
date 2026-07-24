<?php

namespace App\Policies;

use App\Models\OceanImport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OceanImportPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, OceanImport $oceanImport): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, OceanImport $oceanImport): bool
    {
        return true;
    }

    public function delete(User $user, OceanImport $oceanImport): bool
    {
        return true;
    }

    public function restore(User $user, OceanImport $oceanImport): bool
    {
        return true;
    }

    public function forceDelete(User $user, OceanImport $oceanImport): bool
    {
        return true;
    }
}
