<?php

namespace common\assets;

use Yii;
use yii\web\View;

class AddressHelper{
    // Method to inject JavaScript for Google Places and Geolocation
    public static function AutofillAddress(View $view)
    {
        // Register Google Maps JavaScript API with your API key
        $view->registerJs("
            function initAutocomplete() {
                // Initialize Autocomplete for source and destination addresses
                var sourceAddress = new google.maps.places.Autocomplete(document.getElementById('source_address'), {
                    componentRestrictions: { country: 'FI' },
                    language: 'fi'  // Set language to Finnish
                });

                var destinationAddress = new google.maps.places.Autocomplete(document.getElementById('destination_address'), {
                    componentRestrictions: { country: 'FI' },
                    language: 'fi'  // Set language to Finnish
                });

                // Restrict the autocomplete to specific types (geocode)
                sourceAddress.setTypes(['geocode']);
                destinationAddress.setTypes(['geocode']);
            }

            function getCurrentAddress() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        var accuracy = position.coords.accuracy;

                        console.log('Latitude: ' + lat);
                        console.log('Longitude: ' + lng);
                        console.log('Accuracy: ' + accuracy + ' meters');

                        if (accuracy > 50) {
                            alert('The location accuracy is low. Please check your GPS or try again.');
                            return;
                        }

                        var latLng = new google.maps.LatLng(lat, lng);
                        var geocoder = new google.maps.Geocoder();

                        geocoder.geocode({ 'location': latLng }, function(results, status) {
                            if (status === google.maps.GeocoderStatus.OK) {
                                if (results[0]) {
                                    var currentAddress = results[0].formatted_address;
                                    document.getElementById('source_address').value = currentAddress;
                                    console.log('Current address: ' + currentAddress);
                                } else {
                                    console.log('No address found for this location.');
                                }
                            } else {
                                console.log('Geocoder failed due to: ' + status);
                            }
                        });
                    }, function(error) {
                        console.log('Error occurred while retrieving geolocation: ' + error.message);
                    }, {
                        enableHighAccuracy: true,  // Request high accuracy
                        timeout: 10000,            // Wait up to 10 seconds
                        maximumAge: 0              // Do not use cached location
                    });
                } else {
                    console.log('Geolocation is not supported by this browser.');
                }
            }

            function loadGoogleMapsScript() {
                var script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places,geocoding&callback=initAutocomplete';
                script.async = true;
                script.defer = true;
                document.body.appendChild(script);
            }

            loadGoogleMapsScript();
        ", View::POS_END); // Add the JavaScript at the end of the body
    }
}
?>
