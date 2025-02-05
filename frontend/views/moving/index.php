<?php

use frontend\models\Moving;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var frontend\models\MovingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Movings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="moving-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Moving'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'time_created',
            'Customer_name',
            'Customer_phone',
            'Customer_email:email',
            //'from_address',
            //'to_address',
            //'Move_description:ntext',
            //'Distance',
            //'Moving_status',
            //'payment_status',
            //'price',
            //'transaction_id',
            //'partner_id',
            //'Start_time',
            //'End_time',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Moving $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
