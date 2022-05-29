<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnalysisSettings extends Model
{
    use HasFactory;

    protected $table = 'user_analysis_settings';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'indicator',
        'value',
        'expression'
    ];

    public array $expressions = [
        1 => '<',
        2 => '=',
        3 => '>',
        4 => '!=',
    ];

    /**
     * @return mixed
     */
    public function user(): mixed
    {
        return $this->belongsTo(User::class);
    }

    public function getIndicators()
    {
        $stock_data = new StockInformation();
        $table = $stock_data->getTable();
        $indicators = \Schema::getColumnListing($table);
        asort($indicators);

        return $indicators;
    }
}
