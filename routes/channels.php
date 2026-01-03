<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Notifications.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('classroom.{id}', function ($user, $id) {
    return $user->classrooms()->where('id', '=', $id)->exists(); // Check if user is in the classroom
});
