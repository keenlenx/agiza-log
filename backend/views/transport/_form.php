<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\transport $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transport-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'time_created')->textInput() ?>

    <?= $form->field($model, 'Customer_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Customer_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Customer_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Move_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Distance')->textInput() ?>

    <?= $form->field($model, 'Moving_status')->dropDownList([ 'Requested' => 'Requested', 'Approved' => 'Approved', 'Assigned' => 'Assigned', 'Completed' => 'Completed', 'Cancelled' => 'Cancelled', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'payment_status')->dropDownList([ 'Pending' => 'Pending', 'Paid' => 'Paid', 'Failed' => 'Failed', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'transaction_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_id')->textInput() ?>

    <?= $form->field($model, 'assistance')->dropDownList([ 'YES' => 'YES'], ['prompt' => 'NO']) ?>

    <?= $form->field($model, 'assistant_id')->textInput() ?>

    <?= $form->field($model, 'Start_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'End_time')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
