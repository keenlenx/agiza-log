<?php

namespace frontend\assets;

use Yii;
use yii\base\Component;
use frontend\models\Moving;

class PricingHelper extends Component
{
    /**
     * Calculate the delivery price based on the distance.
     *
     * @param float $distance Distance in kilometers.
     * @return float Calculated price with a minimum of 35 euros.
     */
    // function for Delivery prices 
    public static function calculatePrice($distance)
    {
        $multiplier = 1.5; // Price multiplier per kilometer
        $minimumPrice = 35; // Minimum price in euros

        // Calculate the price based on the distance
        $calculatedPrice = $distance * $multiplier;

        // Ensure the price is at least the minimum price
        return round(max($calculatedPrice, $minimumPrice)*1.25,2);
    }
     public static function MovingPrice($distance, $assistance, $elevator, $floor_no)
    {
        $assistantPrice = 0;
        $elevatorPrice = 0;

        // Add assistant price if assistance is needed
        if ($assistance == 'YES') {
            $assistantPrice = 25;
        }

        // Add elevator price if elevator is NOT available (only if floor number is > 0)
        if ($elevator == 'NO' && $floor_no >=2) {
            $elevatorPrice = 10 * $floor_no;
        }

        $multiplier = 1.5; // Price multiplier per kilometer
        $minimumPrice = 70; // Minimum price

        // Calculate base price based on distance
        $calculatedPrice = $distance * $multiplier;

        // Ensure the price is at least the minimum price
        $finalPrice = round(max($calculatedPrice, $minimumPrice) * 1.25, 2) + $assistantPrice + $elevatorPrice;

        return $finalPrice;
    }

     public static function TransportPrice($distance)
    {
        $multiplier = 1.5; // Price multiplier per kilometer
        $minimumPrice = 35; // Minimum price in euros

        // Calculate the price based on the distance
        $calculatedPrice = $distance * $multiplier;

        // Ensure the price is at least the minimum price
        return round(max($calculatedPrice, $minimumPrice)*1.25,2);
    }
    public static function gen_txnid()
    {
        // Generate the transaction ID
        $randomDigits = random_int(1000, 9999);
        $letters = '';
        for ($i = 0; $i < 2; $i++) {
            $letters .= chr(random_int(65, 90)); // ASCII values for A-Z
        }
        $transaction_id = str_shuffle($letters . $randomDigits);

        // Log the transaction ID
        Yii::info("Generated Transaction ID: " . $transaction_id, __METHOD__);

        return $transaction_id;
    }
}