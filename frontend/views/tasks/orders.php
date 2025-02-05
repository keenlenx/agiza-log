<?php

use frontend\models\Moving;
use frontend\models\Transport;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var backend\models\TasksSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'My Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="my-orders container col-sm">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?php // Uncomment if you want a search form at the top
    // echo $this->render('_search', ['model' => $searchModel]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Start_time',
                'label' => 'Date',  // Custom label for the date
                'format' => ['date', 'php:Y-m-d'],  // Format the date to 'Y-m-d'
            ],
            
            // Custom label for the 'Start_time' time
            [
                'attribute' => 'Start_time',
                'label' => 'Time',  // Custom label for the time
                'format' => ['date', 'php:H:i'],  // Format the time to 'H:i' (24-hour format)
            ],
            // 'transaction_id',
            // 'Customer_name',
            // 'Customer_phone',
            'Customer_email:email',
            'from_address',
            'to_address',
            //'Move_description:ntext',
            //'Distance',
            'Moving_status',
            //'payment_status',
            //'price',
            //'partner_id',
            //'End_time',
            //'Stripe_code',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update}',  // Only showing 'view' and 'update' actions
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('View', $url, [
                            'class' => 'btn btn-primary btn-sm',  // Blue button (Bootstrap class)
                            'title' => 'View Details',
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        if ($model->Moving_status !== 'Cancelled' && $model->Moving_status !== 'Completed'){
                        return Html::a('Edit', $url, [
                            'class' => 'btn btn-danger btn-sm',  // Red button (Bootstrap class)
                            'title' => 'Complete Task',
                        ]);}
                        else{
                            return ;
                        };
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                $modelName = strtolower((new \ReflectionClass($model))->getShortName()); // Get model name dynamically
                return Url::toRoute([$modelName . '/' . $action, 'id' => $model->id]); // Construct the URL dynamically
            }
            ]


        ],
        'filterModel' => $searchModel,  // Ensure that the filterModel is passed here
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
        ],
        'layout' => "{items}\n{pager}",  // Customize grid layout to add pager
    ]); ?>

    <?php Pjax::end(); ?>

</div>
