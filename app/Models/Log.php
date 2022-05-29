<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'logs';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'message',
        'created_at',
        'updated_at'
    ];
}