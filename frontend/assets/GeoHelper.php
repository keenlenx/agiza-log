<?php

namespace frontend\assets;

use Yii;
use yii\base\Component;

class GeoHelper extends Component
{
    private static $googleApiKey = 'AIzaSyA18grqAzap1zWB9-LDUBiDv0s2D94EkKM'; // Replace with your actual API key

    /**
     * Get latitude and longitude for a single address.
     *
     * @param string $address
     * @return array|null Returns ['lat' => latitude, 'lng' => longitude] or null on failure.
     */
    public static function geocodeAddress($address)
    {
        $address = urlencode($address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=" . self::$googleApiKey;

        try {
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'OK') {
                $location = $data['results'][0]['geometry']['location'];
                return ['lat' => $location['lat'], 'lng' => $location['lng']];
            } 
            else {
                return Yii::error("Geocoding failed for address '{$address}': " . $data['status'], __METHOD__);
            }
        } catch (\Exception $e) {
            Yii::error('Geocoding request failed: ' . $e->getMessage(), __METHOD__);
        }

        return null;
    }

    /**
     * Geocode the pickup address.
     *
     * @param string $pickupAddress
     * @return array|null
     */
    public static function geocodePickupAddress($pickupAddress)
    {
        return self::geocodeAddress($pickupAddress);
    }

    /**
     * Geocode the delivery address.
     *
     * @param string $movingAddress
     * @return array|null
     */
    public static function geocodeDeliveryAddress($deliveryAddress)
    {
        return self::geocodeAddress($deliveryAddress);
    }
}
