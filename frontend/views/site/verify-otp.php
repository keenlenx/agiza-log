<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="Verify-otp">
    <div class="mt-5 offset-lg-3 col-lg-6">
    <h2>Verify OTP</h2>

    <?php $form = ActiveForm::begin(); ?>
    <? $sessionEmail = Yii::$app->session->get('otp_email');?>
    <!-- Ensure email is passed along as a hidden field -->
    <?= $form->field($model, 'email')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'otp')->textInput(['maxlength' => 6])->label('Enter OTP code sent to your email '.$sessionEmail) ?>

    <div class="form-group mt-2">
        <?= Html::submitButton('Verify', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>