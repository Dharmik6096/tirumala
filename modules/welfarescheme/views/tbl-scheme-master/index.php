<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\welfarescheme\models\TblSchemeMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tbl Scheme Masters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-scheme-master-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tbl Scheme Master', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'scheme_id',
            'scheme_name',
            'start_date',
            'end_date',
            'remarks',
            // 'is_active',
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
