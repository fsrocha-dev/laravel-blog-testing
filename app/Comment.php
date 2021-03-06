<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['title', 'content'];

    //Serialização
    // protected $hidden = ['title'];
    // protected $visible = ['title', 'content'];

    /**
     * Mapeia o relacionamento com o model de posts
     *
     * @return void
     */
    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id', 'id');
    }

    /**
      * Mutator que coloca titulo com primeira letra maiuscula
      *
      * @param [type] $value
      * @return void
      */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }
}
