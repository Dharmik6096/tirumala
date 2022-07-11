<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\transporter\models\TblGateEntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Gate Entries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-gate-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Gate Entry'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'gate_entry_code',
            'union_code',
            'plant_code',
            'mcc_plant_code',
            'bmc_code',
            // 'dcs_code',
            // 'route_code',
            // 'transporter_code',
            // 'vehicle_code',
            // 'date_time_of_collection',
            // 'shift_code',
            // 'define_arrival_time',
            // 'actual_arrival_time',
            // 'grace_time',
            // 'late_by_time',
            // 'responsibility_code',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_org_code',
            // 'originating_org_type',
            // 'originating_type',
            // 'x_col1',
            // 'x_col2',
            // 'x_col3',
            // 'x_col4',
            // 'x_col5',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
