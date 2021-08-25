<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Music;
use Illuminate\Auth\Access\HandlesAuthorization;

class MusicPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function delete(User $user, Music $music)
    {


        return $user->id === $music->userId;
    }
}
