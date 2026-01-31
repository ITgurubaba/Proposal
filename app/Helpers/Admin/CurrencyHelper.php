<?php

namespace App\Helpers\Admin;

use App\Models\Currency;

class CurrencyHelper
{
    public static function getExchangeRate($from = "CAD", $to = "USD"): array
    {
        try
        {
            // Your API key
            $apiKey = getenv('EXCHANGE_RATE_API_KEY');

            // API URL
            $apiUrl = "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD";

            // Fetch data from the URL
            $jsonData = file_get_contents($apiUrl);

            // Decode JSON data
            $exchangeData = json_decode($jsonData, true);

            foreach ($exchangeData['conversion_rates'] as $code=>$rate)
            {
                Currency::updateExchangeRateByCode($code,$rate);
            }

            // Check if the request was successful
            if ($exchangeData['result'] === 'success') {
                // Get the conversion rate for the specified currencies
                $rate = $exchangeData['conversion_rates'][$to];

                return [
                    'success' => true,
                    'data' => $rate,
                ];
            } else {
                // If the request was not successful, return an error message
                return [
                    'success' => false,
                    'error' => 'Failed to fetch exchange rate. ' . $exchangeData['error'],
                ];
            }
        }
        catch (\Exception $exception)
        {
            return [
                'success' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
