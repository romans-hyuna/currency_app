<?php

namespace App\Interfaces;

interface CurrencyConverter
{
    /**
     * Main function for convert currency
     *
     * @param $currency_from
     * @param $currency_to
     * @param $amount
     * @return mixed
     */
    public function convert($currency_from, $currency_to, $amount);

    /**
     * Validation for input data
     *
     * @param $post_data
     * @return mixed
     */
    public function validate($post_data);
}

?>