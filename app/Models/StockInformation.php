<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockInformation extends Model
{
    use HasFactory;

    protected $table = 'stock_informations';
    public $timestamps = false;

    protected $dates = [
        'Last_Dividend_Date'
    ];

    protected $fillable = [
        'stock_id',
        'Market_Volume',
        'Market_Capitalization',
        'Earnings_Average',
        'Revenue_Average',
        'Country',
        'Industry',
        'Sector',
        'Full_Time_Employees',
        'Current_Price',
        'Recommendation',
        'Total_Cash',
        'Total_Cash_Per_Share',
        'EBITDA',
        'Total_Debt',
        'Quick_Ratio',
        'Current_Ratio',
        'Total_Revenue',
        'Debt_To_Equity',
        'Revenue_Per_Share',
        'Return_On_Assets',
        'Return_On_Equity',
        'Gross_Profits',
        'Free_Cashflow',
        'Operating_Cashflow',
        'Earnings_Growth',
        'Revenue_Growth',
        'Profit_Margins',
        'Gross_Margins',
        'EBITDA_Margins',
        'Operating_Margins',
        'Profit_Margins',
        'Enterprise_Value',
        'Forward_PE',
        'Shares_Outstanding',
        'Shares_Outstanding_Percentage',
        'Short_Ratio',
        'Book_Value',
        'Price_To_Book',
        'Earnings_Quarterly_Growth',
        'Net_Income_To_Common',
        'Trailing_EPS',
        'Forward_EPS',
        'PEG_Ratio',
        'Enterprise_To_Revenue',
        'Enterprise_To_EBITDA',
        'Last_Dividend',
        'Last_Dividend_Date',
        'Revenue',
        'Earnings',
    ];

    protected $casts = [
        'Last_Dividend_Date' => 'date'
    ];

    /**
     * @param array $casts
     */
    public function setCasts(array $casts)
    {
        $this->casts = $casts;
    }

    /**
     * @return mixed
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
