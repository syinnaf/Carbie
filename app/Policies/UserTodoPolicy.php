<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserTodo;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserTodoPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, UserTodo $todo)
    {
        if ($user && $todo->user_id === $user->id) {
            return true;
        }
        
        if (!$user && $todo->session_id === session()->getId()) {
            return true;
        }

        return false;
    }

    public function update(?User $user, UserTodo $todo)
    {
        return $this->view($user, $todo);
    }

    public function delete(?User $user, UserTodo $todo)
    {
        return $this->view($user, $todo);
    }
}