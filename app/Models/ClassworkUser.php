<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassworkUser extends Pivot
{
    public function getUpdatedAtColumn() {}
    public function setUpdatedAt($value)
    {
        return $this;
    }
}
