<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\configuration\models\TblMilkCollectionConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Milk Collection Configs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-collection-config-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Milk Collection Config'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'accept_milk',
            'based_on',
            'based_on_disp',
            'can_per_ltr',
            // 'can_warning_per',
            // 'collection_mode',
            // 'collection_quantity_mode',
            // 'bmc_collection_quantity_mode',
            // 'local_milk_sale_quantity_mode',
            // 'sample_milk_quantity_mode',
            // 'created_at',
            // 'created_by',
            // 'default_snf',
            // 'default_snf_value',
            // 'from_machine_clr',
            // 'based_on_local_sale',
            // 'no_disp_local_sale',
            // 'per_local_sale',
            // 'input_clr',
            // 'lr1_for_clr',
            // 'lr2_for_clr',
            // 'ltr_to_kg',
            // 'multi_entry_diff_milk_type',
            // 'multi_entry_same_milk_type',
            // 'no',
            // 'no_disp',
            // 'sample_milk_size',
            // 'seperate_can',
            // 'shift_code',
            // 'shift_code_disp',
            // 'updated_at',
            // 'updated_by',
            // 'variation_in_fat',
            // 'variation_in_fat_block',
            // 'variation_in_qty',
            // 'variation_in_qty_block',
            // 'variation_in_snf',
            // 'variation_in_snf_block',
            // 'union_code',
            // 'weight_setting',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
