<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var frontend\models\Delivery $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deliveries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="delivery-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'transaction_id',
            'sender_name',
            'sender_phone',
            'sender_email:email',
            'recipient_name',
            'recipient_phone',
            'recipient_email:email',
            'source_address:ntext',
            'destination_address:ntext',
            'instructions:ntext',
            'distance',
            'delivery_status',
            'payment_status',
            'price',
            
        ],
    ]) ?>
    <?= Html::a('Pay Now', ['pay/pay', 'txn_id' => $model->transaction_id], ['class' => 'btn btn-success']) ?>


</div>
