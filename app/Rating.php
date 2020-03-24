<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * Método de relação com post ou
     *
     * @return void
     */
    public function ratingable()
    {
        return $this->morphTo();
    }
}
