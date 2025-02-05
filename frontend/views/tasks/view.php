<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var frontend\models\Moving $model */

$this->title = $model->transaction_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="moving-view col-sm-8">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Back', Yii::$app->request->referrer, ['class' => 'btn btn-primary']) ?>
    <p>
        
        <?php if (Yii::$app->user->can('deleteItem')): ?>
    <!-- Only show the delete button if the user has the delete permission -->
        
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>

    <?php endif; ?>
   
        </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'transaction_id',
            'Customer_name',
            'Customer_phone',
            'Customer_email:email',
            'from_address',
            'to_address',
            'Move_description:ntext',
            'Distance',
            'Moving_status',
            'payment_status',
            'price',
            'Stripe_code',
            'partner_id',
            'time_created',
            'Start_time',
            'End_time',
            
        ],
    ]) 

    ?>


</div>

