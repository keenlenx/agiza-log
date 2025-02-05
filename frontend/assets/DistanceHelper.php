<?php

namespace frontend\assets;

use Yii;
use yii\base\Component;

class DistanceHelper extends Component
{
    /**
     * Calculate the distance between two points using the Haversine formula.
     *
     * @param float $lat1 Latitude of the first point.
     * @param float $lon1 Longitude of the first point.
     * @param float $lat2 Latitude of the second point.
     * @param float $lon2 Longitude of the second point.
     * @param string $unit 'km' for kilometers, 'mi' for miles.
     * @return float Distance between the points in the specified unit.
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        $earthRadiusKm = 6371; // Radius of the Earth in kilometers
        $earthRadiusMi = 3958.8; // Radius of the Earth in miles

        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Haversine formula
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;
        $a = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Calculate distance
        $distanceKm = $earthRadiusKm * $c;

        // Convert to miles if needed
        if ($unit === 'mi') {
            return $distanceKm * ($earthRadiusMi / $earthRadiusKm);
        }

        return round($distanceKm,2);
    }
}
?>