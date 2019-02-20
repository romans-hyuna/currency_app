<?php
namespace App;

use App\Exceptions\ApiRequestException;
use App\Exceptions\BadRequestException;

Class Converter
{
    /**
     * Converter service provider here
     *
     * @var object
     */
    protected $convert_service;

    /**
     * Create new converter instance
     */
    public function __construct($config)
    {
        $this->convert_service = CurrencyServiceProvider::getProvider($config['currency_provider'], $config);
    }

    /**
     * Convert currency here
     *
     * @param $post_data
     * @return bool
     */
    public static function convert($post_data)
    {
        try {
            $config = getConfig();
            $provider = new self($config);

            $provider->convert_service->validate($post_data);

            return $provider->convert_service->convert($post_data['currency_from'], $post_data['currency_to'], $post_data['amount']);
        } catch (BadRequestException $e) {
            //log here
            return $e->json($e->getMessage());
        } catch (ApiRequestException $e) {
            //log here
            return $e->json('Service not working, try again');
        } catch (\Exception $e) {
            //log here
            http_response_code(500);
            return json_encode(array('result' => false, 'message' => $e->getMessage()));
        }
    }
}