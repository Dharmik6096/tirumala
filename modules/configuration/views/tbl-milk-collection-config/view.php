<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkCollectionConfig */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Milk Collection Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-milk-collection-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'accept_milk',
            'based_on',
            'based_on_disp',
            'can_per_ltr',
            'can_warning_per',
            'collection_mode',
            'collection_quantity_mode',
            'bmc_collection_quantity_mode',
            'local_milk_sale_quantity_mode',
            'sample_milk_quantity_mode',
            'created_at',
            'created_by',
            'default_snf',
            'default_snf_value',
            'from_machine_clr',
            'based_on_local_sale',
            'no_disp_local_sale',
            'per_local_sale',
            'input_clr',
            'lr1_for_clr',
            'lr2_for_clr',
            'ltr_to_kg',
            'multi_entry_diff_milk_type',
            'multi_entry_same_milk_type',
            'no',
            'no_disp',
            'sample_milk_size',
            'seperate_can',
            'shift_code',
            'shift_code_disp',
            'updated_at',
            'updated_by',
            'variation_in_fat',
            'variation_in_fat_block',
            'variation_in_qty',
            'variation_in_qty_block',
            'variation_in_snf',
            'variation_in_snf_block',
            'union_code',
            'weight_setting',
        ],
    ]) ?>

</div>
