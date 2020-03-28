<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $date = ['deleted_at'];

    /**
     * Mapeia o relacionamento com o model details
     *
     * @return void
     */
    public function details()
    {
        return $this->hasOne('App\Details', 'post_id', 'id')
                    ->withDefault(function ($details) {
                        $details->status = 'rascunho';
                        $details->visibility = 'privado';
                    });
    }

    /**
     * Mapeia o relacionamento com o model de comentários
     *
     * @return void
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'post_id', 'id');
    }

    /**
     * Mapeia o relacionamento com o model de categorias
     *
     * @return void
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_post', 'post_id', 'category_id')
        ->using('App\CategoryPost')
        ->withTimestamps();
        // ->as('relacao')
        // ->wherePivot('active', 1)
        // ->withPivot('username');
    }

    /**
     * Retorna as avaliações relacioandas com o Post
     *
     * @return void
     */
    public function ratings()
    {
        return $this->morphMany('App\Rating', 'ratingable');
    }

    /**
     * Verificando se o post está ativo
     *
     * @param $query
     * @return void
     */
    public function scopeIsApproved($query)
    {
        return $query->where('approved', 1);
    }

    /**
     * Verifica aprovação do post baseado no parametro
     *
     * @param $query
     * @param [integer] $approved
     * @return void
     */
    public function scopeApproved($query, $approved)
    {
        return $query->where('approved', $approved);
    }
}
