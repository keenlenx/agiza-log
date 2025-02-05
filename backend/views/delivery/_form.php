<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Delivery $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="delivery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sender_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sender_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sender_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recipient_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recipient_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recipient_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'source_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'destination_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'instructions')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'delivery_status')->dropDownList([ 'Pending' => 'Pending', 'Transit' => 'Transit', 'Delivered' => 'Delivered', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'payment_status')->dropDownList([ 'Pending' => 'Pending', 'Paid' => 'Paid', 'Failed' => 'Failed', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
