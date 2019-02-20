<?php
namespace App\CurrencyProviders;

use App\Interfaces\CurrencyConverter;
use App\Exceptions\BadRequestException;
use App\Exceptions\ApiRequestException;

Class CurrencyLayerConverterProvider implements CurrencyConverter
{
    /**
     * Currency rate
     *
     * @var int
     */
    protected $currency_rate;

    /**
     * Url for send api request
     *
     * @var
     */
    protected $url;

    /**
     * Api key for provider
     *
     * @var
     */
    protected $api_key;

    /* add cache provider in future*/
    //private $cache_provider;

    public function __construct($config)
    {
        $this->url = $config['provider_credentials'][$config['currency_provider']]['url'];
        $this->api_key = $config['provider_credentials'][$config['currency_provider']]['api_key'];
        $this->currency_rate = 0;
        //$this->cache_provider = new SomeCacheProvider()
    }

    /**
     * Convert function
     *
     * @param $currency_from
     * @param $currency_to
     * @param $amount
     * @return string
     */
    public function convert($currency_from = 'USD', $currency_to = 'EUR', $amount = 0)
    {
        return $this->getRate($currency_from, $currency_to)->getConvertedAmount($amount);
    }

    /**
     * Validation here
     *
     * @param array $post_data
     * @return bool
     */
    public function validate($post_data = array())
    {
        if (empty($post_data['currency_from'])
            || empty($post_data['currency_to'])
            || empty($post_data['amount'])
        ) {
            throw new BadRequestException('Required fields should not be empty');
        }

        return true;
    }

    /**
     * Api call for currency rate
     *
     * @param string $currency_from
     * @param string $currency_to
     * @return $this
     */
    public function getRate($currency_from, $currency_to)
    {
        // first get from cache then call api
//        $rate = $this->getFromCache($currency_from . $currency_to);

//        if (!empty($rate)) {
//            $this->currency_rate = $rate;
//            return $this;
//        }

        $ch = curl_init(
            sprintf('%s?access_key=%s&currencies=%s&source=%s&format=1', $this->url, $this->api_key, $currency_to, $currency_from)
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($json, true);

        if (!isset($result['success']) || empty($result['success'])) {
            if (isset($result['error']) && !empty($result['error'])) {
                throw new ApiRequestException($result['error']['info']);
            }

            throw new ApiRequestException('Something was wrong while convert currency, try again.');
        }

//        $this->addToCache($result['quotes'][$currency_from . $currency_to], $currency_from . $currency_to);

        $this->currency_rate = $result['quotes'][$currency_from . $currency_to];

        return $this;
    }

    /**
     * Return response with currency rate and converted amount
     *
     * @param $amount
     * @return string
     */
    public function getConvertedAmount($amount)
    {
        $response = json_encode(array(
            'success' => true,
            'rate' => $this->currency_rate,
            'amount' => $this->currency_rate * $amount
        ));

        return $response;
    }

    /* We can implement cache here */
    public function addToCache($value, $key)
    {
        return false;
    }

    public function getFromCache($key)
    {
        return false;
    }
}