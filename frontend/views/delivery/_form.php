<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\assests\FormHelper;
/** @var yii\web\View $this */
/** @var frontend\models\Delivery $model */
/** @var yii\widgets\ActiveForm $form */
?>


<div class="delivery-form ">
    <div class="row ">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form-control">
            <?= $form->field($model, 'sender_name')->textInput(['maxlength' => true, 'placeholder' => 'E.g John Doe']) ?>
            <?= $form->field($model, 'sender_phone')->textInput(['maxlength' => true, 'placeholder' => '0123456789']) ?>
            <?= $form->field($model, 'sender_email')->textInput(['maxlength' => true, 'placeholder' => 'name@goodmail.com']) ?>
            <?= $form->field($model, 'recipient_name')->textInput(['maxlength' => true, 'placeholder' => 'E.g Mary Smith']) ?>
            <?= $form->field($model, 'recipient_phone')->textInput(['maxlength' => true, 'placeholder' => '0123456789']) ?>
            <?= $form->field($model, 'recipient_email')->textInput(['maxlength' => true, 'placeholder' => 'receiver@goodmail.com']) ?>

            <!-- Source Address Field with Autocomplete -->
            <?= $form->field($model, 'source_address')->textInput([
                'id' => 'source_address', 
                'placeholder' => 'E.g kuljetie 2 A 22', 
                'class' => 'form-control',
                'onfocus' => 'getCurrentAddress()', 
            ]) ?>

            <!-- Destination Address Field with Autocomplete -->
            <?= $form->field($model, 'destination_address')->textInput(['placeholder' => 'E.g Sapuntie 10 B', 'id' => 'destination_address']) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Next'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
// Register Google Places API Script with Autocomplete initialization
$this->registerJs("
    function initAutocomplete() {
        var sourceAddress = new google.maps.places.Autocomplete(document.getElementById('source_address'), {
            componentRestrictions: { country: 'FI' },
            language: 'fi'
        });
        var destinationAddress = new google.maps.places.Autocomplete(document.getElementById('destination_address'), {
            componentRestrictions: { country: 'FI' },
            language: 'fi'
        });
        sourceAddress.setTypes(['geocode']);
        destinationAddress.setTypes(['geocode']);
    }
    function getCurrentAddress() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'location': latLng }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK && results[0]) {
                        document.getElementById('source_address').value = results[0].formatted_address;
                    }
                });
            });
        }
    }
      // Load Google Maps API Script with your API Key
    function loadGoogleMapsScript() {
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=' + 'AIzaSyA18grqAzap1zWB9-LDUBiDv0s2D94EkKM' + '&libraries=places&callback=initAutocomplete';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
    loadGoogleMapsScript();
", yii\web\View::POS_END);
?>
