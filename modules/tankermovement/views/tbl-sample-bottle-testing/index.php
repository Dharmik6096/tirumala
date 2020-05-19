<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tankermovement\models\TblSampleBottleTestingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Sample Bottle Testings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-sample-bottle-testing-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Sample Bottle Testing'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sample_bottle_testing_code',
            'trip_code',
            'bmc_milk_dispatch_code',
            'bmc_milk_dispatch_txn_code',
            'sample_bottle_testing_date',
            // 'transaction_date',
            // 'milk_quality_type_code',
            // 'milk_type_code',
            // 'fat',
            // 'snf',
            // 'protein',
            // 'union_code',
            // 'plant_code',
            // 'mcc_plant_code',
            // 'bmc_code',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'originating_type',
            // 'originating_org_code',
            // 'originating_org_type',
            // 'x_col1',
            // 'x_col2',
            // 'x_col3',
            // 'x_col4',
            // 'x_col5',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
