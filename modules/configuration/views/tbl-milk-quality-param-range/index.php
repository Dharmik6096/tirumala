<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\configuration\models\TblMilkQualityParamRangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Milk Quality Param Ranges');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-quality-param-range-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Milk Quality Param Range'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'milk_quality_param_range_code',
            'process_name',
            'union_code',
            'org_code',
            'org_type',
            // 'animal_type_code',
            // 'min_fat',
            // 'max_fat',
            // 'min_snf',
            // 'max_snf',
            // 'min_clr',
            // 'max_clr',
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
