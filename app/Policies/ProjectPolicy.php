<?php

namespace App\Policies;

use App\Models\User;
use NeteroMac\MeuFreela\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization; // Padrão do Laravel

class ProjectPolicy
{
    use HandlesAuthorization; // Padrão do Laravel

    /**
     * Determine whether the user can view any models.
     * Qualquer usuário logado pode ver a sua própria lista, então retornamos true.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can create models.
     * Qualquer usuário logado pode criar um projeto, então retornamos true.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can update the project status.
     */
    public function updateStatus(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}