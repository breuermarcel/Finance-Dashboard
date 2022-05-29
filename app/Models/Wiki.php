<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wiki extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wikis';

    public $timestamps = false;

    protected $fillable = [
        'slug',
        'title',
        'teaser',
        'body',
        'author',
        'show'
    ];

    protected $casts = [
        'show' => 'boolean',
    ];

    /**
     * @param array $casts
     */
    public function setCasts(array $casts)
    {
        $this->casts = $casts;
    }

    public static function getPublishedDocuments()
    {
        return self::where('show', true)->get();
    }

    public static function getArchivedDocuments()
    {
        return self::where('show', false)->get();
    }

    /*
    public function getRouteKeyName()
    {
        return 'slug';
    }
    */
}
