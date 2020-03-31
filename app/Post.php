<?php

namespace App;

use App\Events\PostCreated;
use App\Scopes\VisibleScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /**
     * Configurando global scope para ordenação dos posts
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByCreatedAt', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });

        static::addGlobalScope(new VisibleScope);
    }
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $date = ['deleted_at'];

    protected $casts = [
        'approved' => 'boolean'
    ];

    protected $dispatchesEvents = [
        'created' => PostCreated::class
    ];

    protected $appends = ['summary_content'];

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

    /**
     * Filtra posts com categorias relacionadas
     *
     * @param [type] $query
     * @return void
     */
    public function scopeHasCategories($query)
    {
        return $query->whereHas('categories');
    }

    /**
     * Acessor que limita a quantidade de caracteres
     *
     * @param [type] $value
     * @return void
     */
    // public function getContentAttribute($value)
    // {
    //     return mb_strimwidth($value, 0, 50, "...");
    // }
    public function getSummaryContentAttribute()
    {
        return mb_strimwidth($this->content, 0, 50, "...");
    }
}
