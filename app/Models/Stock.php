<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stocks';
    public $timestamps = false;

    protected $fillable = [
        'symbol',
        'wkn',
        'isin',
        'name',
    ];

    /**
     * @return mixed
     */
    public function stockInformation(): mixed
    {
        return $this->hasOne(StockInformation::class);
    }
}
