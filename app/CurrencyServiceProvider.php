<?php
namespace App;

use App\CurrencyProviders\CurrencyLayerConverterProvider;

Class CurrencyServiceProvider
{
    /**
     * Get currency provider instance
     *
     * @return CurrencyLayerConverterProvider
     */
    public static function getProvider($provider_name, $config)
    {
        switch ($provider_name) {
            case 'currency_layer':
                return new CurrencyLayerConverterProvider($config);
                break;
            default:
                return new CurrencyLayerConverterProvider($config);
        }
    }
}