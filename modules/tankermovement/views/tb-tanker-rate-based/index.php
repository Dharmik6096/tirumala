<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tankermovement\models\TblTankerRateBasedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Purchase Rate Baseds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-tanker-rate-based-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Purchase Rate Based'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rate_detail_id',
            'created_at',
            'deleted_at',
            'end_range',
            'deduction_type',
            // 'ref_type',
            // 'fixed_point',
            // 'value',
            // 'kg_rate',
            // 'flg_sentbox_entry',
            // 'quality_param',
            // 'milk_quality_type_code',
            // 'start_range',
            // 'sync_status',
            // 'sync_timestamp',
            // 'updated_at',
            // 'milk_type_code',
            // 'created_by',
            // 'purchase_rate_code',
            // 'updated_by',
            // 'deleted_by',
            // 'is_active',
            // 'is_delete',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
