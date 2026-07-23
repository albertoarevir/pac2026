<?php
namespace App\Policies;
use App\Models\User;


use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Project $project)
    {
        return $user->hasPermissionTo('ELIMINAR PROYECTO');
    }
}