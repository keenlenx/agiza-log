<?php

use frontend\models\Delivery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var frontend\models\DeliverySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Deliveries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Delivery'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'time',
            'sender_name',
            'sender_phone',
            'sender_email:email',
            //'recipient_name',
            //'recipient_phone',
            //'recipient_email:email',
            //'source_address:ntext',
            //'destination_address:ntext',
            //'instructions:ntext',
            //'distance',
            //'delivery_status',
            //'payment_status',
            //'price',
            //'transaction_id',
            //'partner_id',
            //'dropoff_time',
            //'pickup_time',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Delivery $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
