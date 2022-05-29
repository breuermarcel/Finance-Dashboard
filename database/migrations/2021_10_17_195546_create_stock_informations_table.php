<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $table->decimal('Market_Volume', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Market_Capitalization', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Earnings_Average', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Revenue_Average', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Enterprise_Value', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Forward_PE', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Profit_Margins', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Shares_Outstanding', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Shares_Outstanding_Percentage', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Short_Ratio', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Book_Value', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Price_To_Book', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Earnings_Quarterly_Growth', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Net_Income_To_Common', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Trailing_EPS', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Forward_EPS', $precision = 19, $scale = 2)->nullable();
            $table->decimal('PEG_Ratio', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Enterprise_To_Revenue', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Enterprise_To_EBITDA', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Last_Dividend', $precision = 19, $scale = 2)->nullable();
            $table->date('Last_Dividend_Date')->nullable();
            $table->decimal('Revenue', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Earnings', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Current_Price', $precision = 19, $scale = 2)->nullable();
            $table->string('Recommendation')->nullable();
            $table->decimal('Total_Cash', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Total_Cash_Per_Share', $precision = 19, $scale = 2)->nullable();
            $table->decimal('EBITDA', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Total_Debt', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Quick_Ratio', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Current_Ratio', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Total_Revenue', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Debt_To_Equity', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Revenue_Per_Share', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Return_On_Assets', $precision = 10, $scale = 2)->nullable();
            $table->decimal('Return_On_Equity', $precision = 10, $scale = 2)->nullable();
            $table->decimal('Gross_Profits', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Free_Cashflow', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Operating_Cashflow', $precision = 19, $scale = 0)->nullable();
            $table->decimal('Earnings_Growth', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Revenue_Growth', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Gross_Margins', $precision = 19, $scale = 2)->nullable();
            $table->decimal('EBITDA_Margins', $precision = 19, $scale = 2)->nullable();
            $table->decimal('Operating_Margins', $precision = 19, $scale = 2)->nullable();
            $table->string('Country')->nullable();
            $table->string('Industry')->nullable();
            $table->string('Sector')->nullable();
            $table->integer('Full_Time_Employees')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_informations');
    }
}
