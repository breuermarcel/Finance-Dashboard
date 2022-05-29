<?php

namespace App\Services;

use App\Models\Log;
use App\Models\Stock;
use Carbon\Carbon;

class APIService
{
    /**
     * @param string $symbol
     * @param bool $forDB
     * @return array
     */
    public static function getStatistic(string $symbol, bool $forDB): array
    {
        $mergedStatistic = [];
        $emptyArray = [];
        $profile = [];
        $earnings = [];
        $events = [];
        $price = [];
        $keyStatistics = [];
        $finances = [];
        $recommendationTrend = [];

        $modules = 'summaryProfile' . '%2C' . 'financialData' . '%2C' . 'recommendationTrend' . '%2C' . 'calendarEvents' . '%2C' . 'price' . '%2C' . 'defaultKeyStatistics' . '%2C' . 'earnings';

        if ($forDB) {
            $url = 'https://query2.finance.yahoo.com/v10/finance/quoteSummary/' . $symbol . '?formatted=false&modules=' . $modules;
        } else {
            $url = 'https://query2.finance.yahoo.com/v10/finance/quoteSummary/' . $symbol . '?formatted=true&modules=' . $modules;
        }

        try {
            $response = file_get_contents($url);
            $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

            if ($data['quoteSummary']['result'] !== null && $data['quoteSummary']['error'] === null) {
                if (array_key_exists('summaryProfile', $data['quoteSummary']['result'][0])) {
                    $profile = self::generalStatistic($data['quoteSummary']['result'][0]['summaryProfile'], $forDB);
                } else {
                    $profile = self::generalStatistic($emptyArray, $forDB, false);
                }

                if (array_key_exists('earnings', $data['quoteSummary']['result'][0])) {
                    $earnings = self::erningsStatistic($data['quoteSummary']['result'][0]['earnings'], $forDB);
                } else {
                    $earnings = self::erningsStatistic($emptyArray, $forDB, false);
                }

                if (array_key_exists('calendarEvents', $data['quoteSummary']['result'][0]) && array_key_exists('earnings', $data['quoteSummary']['result'][0]['calendarEvents'])) {
                    $events = self::eventsStatistic($data['quoteSummary']['result'][0]['calendarEvents']['earnings'], $forDB);
                } else {
                    $events = self::eventsStatistic($emptyArray, $forDB, false);
                }

                if (array_key_exists('price', $data['quoteSummary']['result'][0])) {
                    $price = self::pricesStatistic($data['quoteSummary']['result'][0]['price'], $forDB);
                } else {
                    $price = self::pricesStatistic($emptyArray, $forDB, false);
                }

                if (array_key_exists('defaultKeyStatistics', $data['quoteSummary']['result'][0])) {
                    $keyStatistics = self::keyStatistics($data['quoteSummary']['result'][0]['defaultKeyStatistics'], $forDB);
                } else {
                    $keyStatistics = self::keyStatistics($emptyArray, $forDB, false);
                }

                if (array_key_exists('financialData', $data['quoteSummary']['result'][0])) {
                    $finances = self::financesStatistic($data['quoteSummary']['result'][0]['financialData'], $forDB);
                } else {
                    $finances = self::financesStatistic($emptyArray, $forDB, false);
                }

                if (!$forDB) {
                    if (array_key_exists('recommendationTrend', $data['quoteSummary']['result'][0])) {
                        $recommendationTrend['recommendationTrend'] = self::recommendationStatistic($data['quoteSummary']['result'][0]['recommendationTrend']['trend'], false);
                    } else {
                        $recommendationTrend['recommendationTrend'] = self::recommendationStatistic($emptyArray, $forDB, false);
                    }
                }

                $mergedStatistic = array_merge($profile, $earnings, $events, $price, $keyStatistics, $finances, $recommendationTrend);
                $mergedStatistic['symbol'] = $symbol;
            }
        } catch (\Exception $e) {
            $stock = Stock::where('symbol', $symbol)->get();

            if ($stock->count() > 0) {
                $stock->delete();
            }

            Log::create([
                'message' => $symbol . ' message: ' . $e->getMessage() . ' detailed: ' . $e->getTraceAsString(),
            ]);
        }

        return $mergedStatistic;
    }

    /**
     * @param array $data
     * @param bool $forDB
     * @param bool $keyExists
     * @return array
     */
    public static function recommendationStatistic(array $data, bool $forDB, bool $keyExists = true): array
    {
        $now = Carbon::now()->format('M Y');
        $oneMonthBefore = Carbon::now()->subMonth()->format('M Y');
        $twoMonthsBefore = Carbon::now()->subMonths(2)->format('M Y');
        $threeMonthsBefore = Carbon::now()->subMonths(3)->format('M Y');

        $recommendationTrend = [];

        if ($keyExists === false && !$forDB) {
            // now
            $recommendationTrend[0]['Strong_Buy'] = '-';
            $recommendationTrend[0]['Buy'] = '-';
            $recommendationTrend[0]['Hold'] = '-';
            $recommendationTrend[0]['Sell'] = '-';
            $recommendationTrend[0]['Strong_Sell'] = '-';
            $recommendationTrend[0]['Date'] = $now;

            // -1 months
            $recommendationTrend[1]['Strong_Buy'] = '-';
            $recommendationTrend[1]['Buy'] = '-';
            $recommendationTrend[1]['Hold'] = '-';
            $recommendationTrend[1]['Sell'] = '-';
            $recommendationTrend[1]['Strong_Sell'] = '-';
            $recommendationTrend[1]['Date'] = $oneMonthBefore;

            // -2 months
            $recommendationTrend[2]['Strong_Buy'] = '-';
            $recommendationTrend[2]['Buy'] = '-';
            $recommendationTrend[2]['Hold'] = '-';
            $recommendationTrend[2]['Sell'] = '-';
            $recommendationTrend[2]['Strong_Sell'] = '-';
            $recommendationTrend[2]['Date'] = $twoMonthsBefore;

            // -3 months
            $recommendationTrend[3]['Strong_Buy'] = '-';
            $recommendationTrend[3]['Buy'] = '-';
            $recommendationTrend[3]['Hold'] = '-';
            $recommendationTrend[3]['Sell'] = '-';
            $recommendationTrend[3]['Strong_Sell'] = '-';
            $recommendationTrend[3]['Date'] = $threeMonthsBefore;
        } else {
            // now
            $recommendationTrend[0]['Date'] = $now;
            if (array_key_exists('strongBuy', $data[0])) {
                $recommendationTrend[0]['Strong_Buy'] = $data[0]['strongBuy'];
            } else {
                $recommendationTrend[0]['Strong_Buy'] = 0;
            }
            if (array_key_exists('buy', $data[0])) {
                $recommendationTrend[0]['Buy'] = $data[0]['buy'];
            } else {
                $recommendationTrend[0]['Buy'] = 0;
            }
            if (array_key_exists('hold', $data[0])) {
                $recommendationTrend[0]['Hold'] = $data[0]['hold'];
            } else {
                $recommendationTrend[0]['Hold'] = 0;
            }
            if (array_key_exists('sell', $data[0])) {
                $recommendationTrend[0]['Sell'] = $data[0]['sell'];
            } else {
                $recommendationTrend[0]['Sell'] = 0;
            }
            if (array_key_exists('strongSell', $data[0])) {
                $recommendationTrend[0]['Strong_Sell'] = $data[0]['strongSell'];
            } else {
                $recommendationTrend[0]['Strong_Sell'] = 0;
            }

            // -1 month
            $recommendationTrend[1]['Date'] = $oneMonthBefore;
            if (array_key_exists('strongBuy', $data[1])) {
                $recommendationTrend[1]['Strong_Buy'] = $data[1]['strongBuy'];
            } else {
                $recommendationTrend[1]['Strong_Buy'] = 0;
            }
            if (array_key_exists('buy', $data[1])) {
                $recommendationTrend[1]['Buy'] = $data[1]['buy'];
            } else {
                $recommendationTrend[1]['Buy'] = 0;
            }
            if (array_key_exists('hold', $data[1])) {
                $recommendationTrend[1]['Hold'] = $data[1]['hold'];
            } else {
                $recommendationTrend[1]['Hold'] = 0;
            }
            if (array_key_exists('sell', $data[1])) {
                $recommendationTrend[1]['Sell'] = $data[1]['sell'];
            } else {
                $recommendationTrend[1]['Sell'] = 0;
            }
            if (array_key_exists('strongSell', $data[1])) {
                $recommendationTrend[1]['Strong_Sell'] = $data[1]['strongSell'];
            } else {
                $recommendationTrend[1]['Strong_Sell'] = 0;
            }

            // -2 months
            $recommendationTrend[2]['Date'] = $twoMonthsBefore;
            if (array_key_exists('strongBuy', $data[2])) {
                $recommendationTrend[2]['Strong_Buy'] = $data[2]['strongBuy'];
            } else {
                $recommendationTrend[2]['Strong_Buy'] = 0;
            }
            if (array_key_exists('buy', $data[2])) {
                $recommendationTrend[2]['Buy'] = $data[2]['buy'];
            } else {
                $recommendationTrend[2]['Buy'] = 0;
            }
            if (array_key_exists('hold', $data[2])) {
                $recommendationTrend[2]['Hold'] = $data[2]['hold'];
            } else {
                $recommendationTrend[2]['Hold'] = 0;
            }
            if (array_key_exists('sell', $data[2])) {
                $recommendationTrend[2]['Sell'] = $data[2]['sell'];
            } else {
                $recommendationTrend[2]['Sell'] = 0;
            }
            if (array_key_exists('strongSell', $data[2])) {
                $recommendationTrend[2]['Strong_Sell'] = $data[2]['strongSell'];
            } else {
                $recommendationTrend[2]['Strong_Sell'] = 0;
            }

            // -3 months
            $recommendationTrend[3]['Date'] = $threeMonthsBefore;
            if (array_key_exists('strongBuy', $data[3])) {
                $recommendationTrend[3]['Strong_Buy'] = $data[3]['strongBuy'];
            } else {
                $recommendationTrend[3]['Strong_Buy'] = 0;
            }
            if (array_key_exists('buy', $data[3])) {
                $recommendationTrend[3]['Buy'] = $data[3]['buy'];
            } else {
                $recommendationTrend[3]['Buy'] = 0;
            }
            if (array_key_exists('hold', $data[3])) {
                $recommendationTrend[3]['Hold'] = $data[3]['hold'];
            } else {
                $recommendationTrend[3]['Hold'] = 0;
            }
            if (array_key_exists('sell', $data[3])) {
                $recommendationTrend[3]['Sell'] = $data[3]['sell'];
            } else {
                $recommendationTrend[3]['Sell'] = 0;
            }
            if (array_key_exists('strongSell', $data[3])) {
                $recommendationTrend[3]['Strong_Sell'] = $data[3]['strongSell'];
            } else {
                $recommendationTrend[3]['Strong_Sell'] = 0;
            }
        }

        return $recommendationTrend;
    }

    /**
     * @param array $data
     * @param bool $forDB
     * @param bool $keyExists
     * @return array
     */
    public static function generalStatistic(array $data, bool $forDB, bool $keyExists = true): array
    {
        $general = [];

        if ($keyExists === false && !$forDB) {
            $general['Country'] = '-';
            $general['Industry'] = '-';
            $general['Sector'] = '-';
            $general['Full_Time_Employees'] = '-';
            $general['Address'] = '-';
            $general['ZIP'] = '-';
            $general['City'] = '-';
            $general['Summary'] = '-';
            $general['Website'] = '-';
        } else if ($forDB) {
            if (array_key_exists('country', $data)) {
                $general['Country'] = $data['country'];
            }
            if (array_key_exists('industry', $data)) {
                $general['Industry'] = $data['industry'];
            }
            if (array_key_exists('sector', $data)) {
                $general['Sector'] = $data['sector'];
            }
            if (array_key_exists('fullTimeEmployees', $data)) {
                $general['Full_Time_Employees'] = $data['fullTimeEmployees'];
            }
        } else {
            if (array_key_exists('country', $data)) {
                $general['Country'] = $data['country'];
            } else {
                $general['Country'] = '-';
            }
            if (array_key_exists('industry', $data)) {
                $general['Industry'] = $data['industry'];
            } else {
                $general['Industry'] = '-';
            }
            if (array_key_exists('sector', $data)) {
                $general['Sector'] = $data['sector'];
            } else {
                $general['Sector'] = '-';
            }
            if (array_key_exists('fullTimeEmployees', $data)) {
                $general['Full_Time_Employees'] = $data['fullTimeEmployees'];
            } else {
                $general['Full_Time_Employees'] = '-';
            }
            if (array_key_exists('address1', $data)) {
                $general['Address'] = $data['address1'];
            } else {
                $general['Address'] = '-';
            }
            if (array_key_exists('zip', $data)) {
                $general['ZIP'] = $data['zip'];
            } else {
                $general['ZIP'] = '-';
            }
            if (array_key_exists('city', $data)) {
                $general['City'] = $data['city'];
            } else {
                $general['City'] = '-';
            }
            if (array_key_exists('longBusinessSummary', $data)) {
                $general['Summary'] = $data['longBusinessSummary'];
            } else {
                $general['Summary'] = '-';
            }
            if (array_key_exists('website', $data)) {
                $general['Website'] = $data['website'];
            } else {
                $general['Website'] = '-';
            }
        }

        return $general;
    }

    public static function erningsStatistic(array $data, bool $forDB, bool $keyExists = true): array
    {
        $earnings = [];

        if ($keyExists === false && !$forDB) {
            $earnings['Revenue'] = '-';
            $earnings['Earnings'] = '-';
        } else if ($forDB) {
            if (array_key_exists('financialsChart', $data) && array_key_exists('yearly', $data['financialsChart'])) {
                $last_key = array_key_last($data['financialsChart']['yearly']);

                if ($last_key !== null) {
                    $earnings['Revenue'] = $data['financialsChart']['yearly'][$last_key]['revenue'];
                    $earnings['Earnings'] = $data['financialsChart']['yearly'][$last_key]['earnings'];
                }
            }
        } else if (array_key_exists('financialsChart', $data) && array_key_exists('yearly', $data['financialsChart'])) {
            $last_key = array_key_last($data['financialsChart']['yearly']);

            if ($last_key !== null) {
                if (array_key_exists('fmt', $data['financialsChart']['yearly'][$last_key]['revenue'])) {
                    $earnings['Revenue'] = $data['financialsChart']['yearly'][$last_key]['revenue']['fmt'];
                } else {
                    $earnings['Revenue'] = '-';
                }
                if (array_key_exists('fmt', $data['financialsChart']['yearly'][$last_key]['earnings'])) {
                    $earnings['Earnings'] = $data['financialsChart']['yearly'][$last_key]['earnings']['fmt'];
                } else {
                    $earnings['Earnings'] = '-';
                }
            } else {
                $earnings['Revenue'] = '-';
                $earnings['Earnings'] = '-';
            }
        } else {
            $earnings['Revenue'] = '-';
            $earnings['Earnings'] = '-';
        }

        return $earnings;
    }

    /**
     * @param array $data
     * @param bool $forDB
     * @param bool $keyExists
     * @return array
     */
    public static function eventsStatistic(array $data, bool $forDB, bool $keyExists = true): array
    {
        $events = [];

        if ($keyExists === false && !$forDB) {
            $events['Earnings_Average'] = '-';
            $events['Revenue_Average'] = '-';
        } else if ($forDB) {
            if (array_key_exists('earningsAverage', $data)) {
                $events['Earnings_Average'] = $data['earningsAverage'];
            }
            if (array_key_exists('revenueAverage', $data)) {
                $events['Revenue_Average'] = $data['revenueAverage'];
            }
        } else {
            if (array_key_exists('earningsAverage', $data) && array_key_exists('fmt', $data['earningsAverage'])) {
                $events['Earnings_Average'] = $data['earningsAverage']['fmt'];
            } else {
                $events['Earnings_Average'] = '-';
            }
            if (array_key_exists('revenueAverage', $data) && array_key_exists('fmt', $data['revenueAverage'])) {
                $events['Revenue_Average'] = $data['revenueAverage']['fmt'];
            } else {
                $events['Revenue_Average'] = '-';
            }
        }

        return $events;
    }

    /**
     * @param array $data
     * @param bool $forDB
     * @param bool $keyExists
     * @return array
     */
    public static function keyStatistics(array $data, bool $forDB, bool $keyExists = true): array
    {
        $key = [];

        if ($keyExists === false && !$forDB) {
            $key['Enterprise_Value'] = '-';
            $key['Forward_PE'] = '-';
            $key['Profit_Margins'] = '-';
            $key['Shares_Outstanding'] = '-';
            $key['Shares_Outstanding_Percentage'] = '-';
            $key['Short_Ratio'] = '-';
            $key['Shares_Short'] = '-';
            $key['Book_Value'] = '-';
            $key['Shares_Outstanding_Percentage'] = '-';
            $key['Price_To_Book'] = '-';
            $key['Earnings_Quarterly_Growth'] = '-';
            $key['Net_Income_To_Common'] = '-';
            $key['Trailing_EPS'] = '-';
            $key['Forward_EPS'] = '-';
            $key['PEG_Ratio'] = '-';
            $key['Enterprise_To_Revenue'] = '-';
            $key['Enterprise_To_EBITDA'] = '-';
            $key['Last_Dividend'] = '-';
            $key['Last_Dividend_Date'] = '-';
            $key['Last_Split_Factor'] = '-';
            $key['Last_Split_Date'] = '-';
        } else if ($forDB) {
            if (array_key_exists('enterpriseValue', $data)) {
                $key['Enterprise_Value'] = $data['enterpriseValue'];
            }
            if (array_key_exists('forwardPE', $data)) {
                $key['Forward_PE'] = $data['forwardPE'];
            }
            if (array_key_exists('profitMargins', $data)) {
                $key['Profit_Margins'] = $data['profitMargins'];
            }
            if (array_key_exists('sharesOutstanding', $data)) {
                $key['Shares_Outstanding'] = $data['sharesOutstanding'];
            }
            if (array_key_exists('sharesPercentSharesOut', $data)) {
                $key['Shares_Outstanding_Percentage'] = $data['sharesPercentSharesOut'];
            }
            if (array_key_exists('shortRatio', $data)) {
                $key['Short_Ratio'] = $data['shortRatio'];
            }
            if (array_key_exists('bookValue', $data)) {
                $key['Book_Value'] = $data['bookValue'];
            }
            if (array_key_exists('sharesPercentSharesOut', $data)) {
                $key['Shares_Outstanding_Percentage'] = $data['sharesPercentSharesOut'];
            }
            if (array_key_exists('priceToBook', $data)) {
                $key['Price_To_Book'] = $data['priceToBook'];
            }
            if (array_key_exists('earningsQuarterlyGrowth', $data)) {
                $key['Earnings_Quarterly_Growth'] = $data['earningsQuarterlyGrowth'];
            }
            if (array_key_exists('netIncomeToCommon', $data)) {
                $key['Net_Income_To_Common'] = $data['netIncomeToCommon'];
            }
            if (array_key_exists('trailingEps', $data)) {
                $key['Trailing_EPS'] = $data['trailingEps'];
            }
            if (array_key_exists('forwardEps', $data)) {
                $key['Forward_EPS'] = $data['forwardEps'];
            }
            if (array_key_exists('pegRatio', $data)) {
                $key['PEG_Ratio'] = $data['pegRatio'];
            }
            if (array_key_exists('enterpriseToRevenue', $data)) {
                $key['Enterprise_To_Revenue'] = $data['enterpriseToRevenue'];
            }
            if (array_key_exists('enterpriseToEbitda', $data)) {
                $key['Enterprise_To_EBITDA'] = $data['enterpriseToEbitda'];
            }
            if (array_key_exists('lastDividendValue', $data)) {
                $key['Last_Dividend'] = $data['lastDividendValue'];
            }
            if (array_key_exists('lastDividendDate', $data)) {
                $key['Last_Dividend_Date'] = $data['lastDividendDate'];
            }
        } else {
            if (array_key_exists('enterpriseValue', $data) && array_key_exists('fmt', $data['enterpriseValue'])) {
                $key['Enterprise_Value'] = $data['enterpriseValue']['fmt'];
            } else {
                $key['Enterprise_Value'] = '-';
            }
            if (array_key_exists('forwardPE', $data) && array_key_exists('fmt', $data['forwardPE'])) {
                $key['Forward_PE'] = $data['forwardPE']['fmt'];
            } else {
                $key['Forward_PE'] = '-';
            }
            if (array_key_exists('profitMargins', $data) && array_key_exists('fmt', $data['profitMargins'])) {
                $key['Profit_Margins'] = $data['profitMargins']['fmt'];
            } else {
                $key['Profit_Margins'] = '-';
            }
            if (array_key_exists('sharesOutstanding', $data) && array_key_exists('fmt', $data['sharesOutstanding'])) {
                $key['Shares_Outstanding'] = $data['sharesOutstanding']['fmt'];
            } else {
                $key['Shares_Outstanding'] = '-';
            }
            if (array_key_exists('sharesPercentSharesOut', $data) && array_key_exists('fmt', $data['sharesPercentSharesOut'])) {
                $key['Shares_Outstanding_Percentage'] = $data['sharesPercentSharesOut']['fmt'];
            } else {
                $key['Shares_Outstanding_Percentage'] = '-';
            }
            if (array_key_exists('shortRatio', $data) && array_key_exists('fmt', $data['shortRatio'])) {
                $key['Short_Ratio'] = $data['shortRatio']['fmt'];
            } else {
                $key['Short_Ratio'] = '-';
            }
            if (array_key_exists('bookValue', $data) && array_key_exists('fmt', $data['bookValue'])) {
                $key['Book_Value'] = $data['bookValue']['fmt'];
            } else {
                $key['Book_Value'] = '-';
            }
            if (array_key_exists('sharesPercentSharesOut', $data) && array_key_exists('fmt', $data['sharesPercentSharesOut'])) {
                $key['Shares_Outstanding_Percentage'] = $data['sharesPercentSharesOut']['fmt'];
            } else {
                $key['Shares_Outstanding_Percentage'] = '-';
            }
            if (array_key_exists('priceToBook', $data) && array_key_exists('fmt', $data['priceToBook'])) {
                $key['Price_To_Book'] = $data['priceToBook']['fmt'];
            } else {
                $key['Price_To_Book'] = '-';
            }
            if (array_key_exists('earningsQuarterlyGrowth', $data) && array_key_exists('fmt', $data['earningsQuarterlyGrowth'])) {
                $key['Earnings_Quarterly_Growth'] = $data['earningsQuarterlyGrowth']['fmt'];
            } else {
                $key['Earnings_Quarterly_Growth'] = '-';
            }
            if (array_key_exists('netIncomeToCommon', $data) && array_key_exists('fmt', $data['netIncomeToCommon'])) {
                $key['Net_Income_To_Common'] = $data['netIncomeToCommon']['fmt'];
            } else {
                $key['Net_Income_To_Common'] = '-';
            }
            if (array_key_exists('trailingEps', $data) && array_key_exists('fmt', $data['trailingEps'])) {
                $key['Trailing_EPS'] = $data['trailingEps']['fmt'];
            } else {
                $key['Trailing_EPS'] = '-';
            }
            if (array_key_exists('forwardEps', $data) && array_key_exists('fmt', $data['forwardEps'])) {
                $key['Forward_EPS'] = $data['forwardEps']['fmt'];
            } else {
                $key['Forward_EPS'] = '-';
            }
            if (array_key_exists('pegRatio', $data) && array_key_exists('fmt', $data['pegRatio'])) {
                $key['PEG_Ratio'] = $data['pegRatio']['fmt'];
            } else {
                $key['PEG_Ratio'] = '-';
            }
            if (array_key_exists('enterpriseToRevenue', $data) && array_key_exists('fmt', $data['enterpriseToRevenue'])) {
                $key['Enterprise_To_Revenue'] = $data['enterpriseToRevenue']['fmt'];
            } else {
                $key['Enterprise_To_Revenue'] = '-';
            }
            if (array_key_exists('enterpriseToEbitda', $data) && array_key_exists('fmt', $data['enterpriseToEbitda'])) {
                $key['Enterprise_To_EBITDA'] = $data['enterpriseToEbitda']['fmt'];
            } else {
                $key['Enterprise_To_EBITDA'] = '-';
            }
            if (array_key_exists('lastDividendValue', $data) && array_key_exists('fmt', $data['lastDividendValue'])) {
                $key['Last_Dividend'] = $data['lastDividendValue']['fmt'];
            } else {
                $key['Last_Dividend'] = '-';
            }
            if (array_key_exists('lastDividendDate', $data) && array_key_exists('raw', $data['lastDividendDate'])) {
                $key['Last_Dividend_Date'] = date('d.m.Y', $data['lastDividendDate']['raw']);
            } else {
                $key['Last_Dividend_Date'] = '-';
            }
            if (array_key_exists('sharesShort', $data) && array_key_exists('fmt', $data['sharesShort'])) {
                $key['Shares_Short'] = $data['sharesShort']['fmt'];
            } else {
                $key['Shares_Short'] = '-';
            }
            if (array_key_exists('lastSplitFactor', $data) && $data['lastSplitFactor'] !== null) {
                $key['Last_Split_Factor'] = $data['lastSplitFactor'];
            } else {
                $key['Last_Split_Factor'] = '-';
            }
            if (array_key_exists('lastSplitDate', $data) && array_key_exists('raw', $data['lastSplitDate'])) {
                $key['Last_Split_Date'] = date('d.m.Y', $data['lastSplitDate']['raw']);
            } else {
                $key['Last_Split_Date'] = '-';
            }
        }

        return $key;
    }

    /**
     * @param array $data
     * @param bool $forDB
     * @param bool $keyExists
     * @return array
     */
    public static function pricesStatistic(array $data, bool $forDB, bool $keyExists = true): array
    {
        $prices = [];

        if ($keyExists === false && !$forDB) {
            $prices['Market_Volume'] = '-';
            $prices['Market_Capitalization'] = '-';
            $prices['Currency_Symbol'] = '-';
        } else if ($forDB) {
            if (array_key_exists('regularMarketVolume', $data)) {
                $prices['Market_Volume'] = $data['regularMarketVolume'];
            }
            if (array_key_exists('marketCap', $data)) {
                $prices['Market_Capitalization'] = $data['marketCap'];
            }
        } else {
            if (array_key_exists('regularMarketVolume', $data) && array_key_exists('fmt', $data['regularMarketVolume'])) {
                $prices['Market_Volume'] = $data['regularMarketVolume']['fmt'];
            } else {
                $prices['Market_Volume'] = '-';
            }
            if (array_key_exists('marketCap', $data) && array_key_exists('fmt', $data['marketCap'])) {
                $prices['Market_Capitalization'] = $data['marketCap']['fmt'];
            } else {
                $prices['Market_Capitalization'] = '-';
            }
            if (array_key_exists('currencySymbol', $data)) {
                $prices['Currency_Symbol'] = $data['currencySymbol'];
            } else {
                $prices['Currency_Symbol'] = '-';
            }
        }

        return $prices;
    }

    /**
     * @param array $data
     * @param bool $forDB
     * @param bool $keyExists
     * @return array
     */
    public static function financesStatistic(array $data, bool $forDB, bool $keyExists = true): array
    {
        $finance = [];

        if ($keyExists === false && !$forDB) {
            $finance['Current_Price'] = '-';
            $finance['Recommendation'] = 'None';
            $finance['Total_Cash'] = '-';
            $finance['Total_Cash_Per_Share'] = '-';
            $finance['EBITDA'] = '-';
            $finance['Total_Debt'] = '-';
            $finance['Quick_Ratio'] = '-';
            $finance['Current_Ratio'] = '-';
            $finance['Total_Revenue'] = '-';
            $finance['Debt_To_Equity'] = '-';
            $finance['Revenue_Per_Share'] = '-';
            $finance['Return_On_Assets'] = '-';
            $finance['Return_On_Equity'] = '-';
            $finance['Gross_Profits'] = '-';
            $finance['Free_Cashflow'] = '-';
            $finance['Operating_Cashflow'] = '-';
            $finance['Revenue_Growth'] = '-';
            $finance['Earnings_Growth'] = '-';
            $finance['Gross_Margins'] = '-';
            $finance['EBITDA_Margins'] = '-';
            $finance['Operating_Margins'] = '-';
            $finance['Profit_Margins'] = '-';
            $finance['Financial_Currency'] = '-';
            $finance['Revenue_per_Share'] = '-';
        } else if ($forDB) {
            if (array_key_exists('currentPrice', $data)) {
                $finance['Current_Price'] = $data['currentPrice'];
            }
            if (array_key_exists('recommendationKey', $data)) {
                $finance['Recommendation'] = $data['recommendationKey'];
            }
            if (array_key_exists('totalCash', $data)) {
                $finance['Total_Cash'] = $data['totalCash'];
            }
            if (array_key_exists('totalCashPerShare', $data)) {
                $finance['Total_Cash_Per_Share'] = $data['totalCashPerShare'];
            }
            if (array_key_exists('ebitda', $data)) {
                $finance['EBITDA'] = $data['ebitda'];
            }
            if (array_key_exists('totalDebt', $data)) {
                $finance['Total_Debt'] = $data['totalDebt'];
            }
            if (array_key_exists('quickRatio', $data)) {
                $finance['Quick_Ratio'] = $data['quickRatio'];
            }
            if (array_key_exists('currentRatio', $data)) {
                $finance['Current_Ratio'] = $data['currentRatio'];
            }
            if (array_key_exists('totalRevenue', $data)) {
                $finance['Total_Revenue'] = $data['totalRevenue'];
            }
            if (array_key_exists('debtToEquity', $data)) {
                $finance['Debt_To_Equity'] = $data['debtToEquity'];
            }
            if (array_key_exists('revenuePerShare', $data)) {
                $finance['Debt_To_Equity'] = $data['revenuePerShare'];
            }
            if (array_key_exists('returnOnAssets', $data)) {
                $finance['Return_On_Assets'] = $data['returnOnAssets'];
            }
            if (array_key_exists('returnOnEquity', $data)) {
                $finance['Return_On_Equity'] = $data['returnOnEquity'];
            }
            if (array_key_exists('grossProfits', $data)) {
                $finance['Gross_Profits'] = $data['grossProfits'];
            }
            if (array_key_exists('freeCashflow', $data)) {
                $finance['Free_Cashflow'] = $data['freeCashflow'];
            }
            if (array_key_exists('operatingCashflow', $data)) {
                $finance['Operating_Cashflow'] = $data['operatingCashflow'];
            }
            if (array_key_exists('revenueGrowth', $data)) {
                $finance['Revenue_Growth'] = $data['revenueGrowth'];
            }
            if (array_key_exists('earningsGrowth', $data)) {
                $finance['Earnings_Growth'] = $data['earningsGrowth'];
            }
            if (array_key_exists('grossMargins', $data)) {
                $finance['Gross_Margins'] = $data['grossMargins'];
            }
            if (array_key_exists('ebitdaMargins', $data)) {
                $finance['EBITDA_Margins'] = $data['ebitdaMargins'];
            }
            if (array_key_exists('operatingMargins', $data)) {
                $finance['Operating_Margins'] = $data['operatingMargins'];
            }
            if (array_key_exists('profitMargins', $data)) {
                $finance['Profit_Margins'] = $data['profitMargins'];
            }
        } else {
            if (array_key_exists('currentPrice', $data) && array_key_exists('fmt', $data['currentPrice'])) {
                $finance['Current_Price'] = $data['currentPrice']['fmt'];
            } else {
                $finance['Current_Price'] = '-';
            }
            if (array_key_exists('recommendationKey', $data) && $data['recommendationKey'] !== 'none') {
                $finance['Recommendation'] = $data['recommendationKey'];
            } else {
                $finance['Recommendation'] = 'None';
            }
            if (array_key_exists('totalCash', $data) && array_key_exists('fmt', $data['totalCash'])) {
                $finance['Total_Cash'] = $data['totalCash']['fmt'];
            } else {
                $finance['Total_Cash'] = '-';
            }
            if (array_key_exists('totalCashPerShare', $data) && array_key_exists('fmt', $data['totalCashPerShare'])) {
                $finance['Total_Cash_Per_Share'] = $data['totalCashPerShare']['fmt'];
            } else {
                $finance['Total_Cash_Per_Share'] = '-';
            }
            if (array_key_exists('ebitda', $data) && array_key_exists('fmt', $data['ebitda'])) {
                $finance['EBITDA'] = $data['ebitda']['fmt'];
            } else {
                $finance['EBITDA'] = '-';
            }
            if (array_key_exists('totalDebt', $data) && array_key_exists('fmt', $data['totalDebt'])) {
                $finance['Total_Debt'] = $data['totalDebt']['fmt'];
            } else {
                $finance['Total_Debt'] = '-';
            }
            if (array_key_exists('quickRatio', $data) && array_key_exists('fmt', $data['quickRatio'])) {
                $finance['Quick_Ratio'] = $data['quickRatio']['fmt'];
            } else {
                $finance['Quick_Ratio'] = '-';
            }
            if (array_key_exists('currentRatio', $data) && array_key_exists('fmt', $data['currentRatio'])) {
                $finance['Current_Ratio'] = $data['currentRatio']['fmt'];
            } else {
                $finance['Current_Ratio'] = '-';
            }
            if (array_key_exists('totalRevenue', $data) && array_key_exists('fmt', $data['totalRevenue'])) {
                $finance['Total_Revenue'] = $data['totalRevenue']['fmt'];
            } else {
                $finance['Total_Revenue'] = '-';
            }
            if (array_key_exists('debtToEquity', $data) && array_key_exists('fmt', $data['debtToEquity'])) {
                $finance['Debt_To_Equity'] = $data['debtToEquity']['fmt'];
            } else {
                $finance['Debt_To_Equity'] = '-';
            }
            if (array_key_exists('revenuePerShare', $data) && array_key_exists('fmt', $data['revenuePerShare'])) {
                $finance['Revenue_Per_Share'] = $data['revenuePerShare']['fmt'];
            } else {
                $finance['Revenue_Per_Share'] = '-';
            }
            if (array_key_exists('returnOnAssets', $data) && array_key_exists('fmt', $data['returnOnAssets'])) {
                $finance['Return_On_Assets'] = $data['returnOnAssets']['fmt'];
            } else {
                $finance['Return_On_Assets'] = '-';
            }
            if (array_key_exists('returnOnEquity', $data) && array_key_exists('fmt', $data['returnOnEquity'])) {
                $finance['Return_On_Equity'] = $data['returnOnEquity']['fmt'];
            } else {
                $finance['Return_On_Equity'] = '-';
            }
            if (array_key_exists('grossProfits', $data) && array_key_exists('fmt', $data['grossProfits'])) {
                $finance['Gross_Profits'] = $data['grossProfits']['fmt'];
            } else {
                $finance['Gross_Profits'] = '-';
            }
            if (array_key_exists('freeCashflow', $data) && array_key_exists('fmt', $data['freeCashflow'])) {
                $finance['Free_Cashflow'] = $data['freeCashflow']['fmt'];
            } else {
                $finance['Free_Cashflow'] = '-';
            }
            if (array_key_exists('operatingCashflow', $data) && array_key_exists('fmt', $data['operatingCashflow'])) {
                $finance['Operating_Cashflow'] = $data['operatingCashflow']['fmt'];
            } else {
                $finance['Operating_Cashflow'] = '-';
            }
            if (array_key_exists('revenueGrowth', $data) && array_key_exists('fmt', $data['revenueGrowth'])) {
                $finance['Revenue_Growth'] = $data['revenueGrowth']['fmt'];
            } else {
                $finance['Revenue_Growth'] = '-';
            }
            if (array_key_exists('earningsGrowth', $data) && array_key_exists('fmt', $data['earningsGrowth'])) {
                $finance['Earnings_Growth'] = $data['earningsGrowth']['fmt'];
            } else {
                $finance['Earnings_Growth'] = '-';
            }
            if (array_key_exists('grossMargins', $data) && array_key_exists('fmt', $data['grossMargins'])) {
                $finance['Gross_Margins'] = $data['grossMargins']['fmt'];
            } else {
                $finance['Gross_Margins'] = '-';
            }
            if (array_key_exists('ebitdaMargins', $data) && array_key_exists('fmt', $data['ebitdaMargins'])) {
                $finance['EBITDA_Margins'] = $data['ebitdaMargins']['fmt'];
            } else {
                $finance['EBITDA_Margins'] = '-';
            }
            if (array_key_exists('operatingMargins', $data) && array_key_exists('fmt', $data['operatingMargins'])) {
                $finance['Operating_Margins'] = $data['operatingMargins']['fmt'];
            } else {
                $finance['Operating_Margins'] = '-';
            }
            if (array_key_exists('profitMargins', $data) && array_key_exists('fmt', $data['profitMargins'])) {
                $finance['Profit_Margins'] = $data['profitMargins']['fmt'];
            } else {
                $finance['Profit_Margins'] = '-';
            }
            if (array_key_exists('financialCurrency', $data)) {
                $finance['Financial_Currency'] = $data['financialCurrency'];
            } else {
                $finance['Financial_Currency'] = '-';
            }
            if (array_key_exists('revenuePerShare', $data) && array_key_exists('fmt', $data['revenuePerShare'])) {
                $finance['Revenue_per_Share'] = $data['revenuePerShare']['fmt'];
            } else {
                $finance['Revenue_per_Share'] = '-';
            }
        }

        return $finance;
    }

    /**
     * @param string $symbol
     * @param int $days
     * @return array
     */
    public static function stockHistory(string $symbol, int $days): array
    {
        $response_data = [];

        try {
            $url = 'https://query2.finance.yahoo.com/v8/finance/chart/' . "?symbol=" . $symbol . "&period1=" . Carbon::now()->subDays($days)->format('U') . "&period2=9999999999&interval=1d";
            $response = file_get_contents($url);
            $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

            if (array_key_exists('adjclose', $data['chart']['result']['0']['indicators']['adjclose'][0])) {
                foreach ($data['chart']['result'][0]['timestamp'] as $d_key => $date) {
                    foreach ($data['chart']['result'][0]['indicators']['adjclose'][0]['adjclose'] as $v_key => $value) {
                        if (($value !== null) && $d_key === $v_key) {
                            $response_data[$date] = [
                                'adj_close' => $value,
                                'date' => Carbon::parse($date)->format('d.m.Y')
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {

        }

        return $response_data;
    }
}