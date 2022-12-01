<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\welfarescheme\models\TblSchemeApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tbl Scheme Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-application-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tbl Scheme Application', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'application_id',
            'scheme_id',
            'member_code',
            'application_date',
            'min_pouring_day',
            // 'min_pouring_qty',
            // 'actual_pouring_day',
            // 'actual_pouring_qty',
            // 'remarks',
            // 'scheme_value',
            // 'approved_value',
            // 'application_status',
            // 'status_date',
            // 'status_by',
            // 'status_remarks',
            // 'dcs_code',
            // 'bmc_code',
            // 'mcc_plant_code',
            // 'plant_code',
            // 'union_code',
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
