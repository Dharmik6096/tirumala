<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tms\models\TblTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Tasks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Task'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'task_code',
            'task_type_code',
            'form_type_code',
            'task_performed_for',
            'title',
            // 'description',
            // 'task_datetime',
            // 'is_cancel',
            // 'user_code',
            // 'route_code',
            // 'bmc_code',
            // 'mcc_plant_code',
            // 'plant_code',
            // 'union_code',
            // 'is_notified',
            // 'notified_datetime',
            // 'pick_datetime',
            // 'response_datetime',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_type',
            // 'originating_org_code',
            // 'originating_org_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
