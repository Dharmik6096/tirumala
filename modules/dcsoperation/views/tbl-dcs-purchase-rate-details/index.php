<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\dcsoperation\models\TblDcsPurchaseRateAutoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Purchase Rate Autos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-purchase-rate-auto-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Purchase Rate Auto'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Id',
            'wef_date',
            'animal_type',
            'created_at',
            'deleted_at',
            // 'fat',
            // 'flg_sentbox_entry',
            // 'is_delete',
            // 'ratetype',
            // 'rtpl',
            // 'snf',
            // 'sync_status',
            // 'sync_timestamp',
            // 'updated_at',
            // 'milk_quality_type_code',
            // 'created_by',
            // 'deleted_by',
            // 'purchase_code',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
