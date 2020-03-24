<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public function ratingable()
    {
        return $this->morphTo();
    }
}
