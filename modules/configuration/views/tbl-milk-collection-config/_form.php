<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\configuration\models\TblMilkCollectionConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-milk-collection-config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'accept_milk')->textInput() ?>

    <?= $form->field($model, 'based_on')->textInput() ?>

    <?= $form->field($model, 'based_on_disp')->textInput() ?>

    <?= $form->field($model, 'can_per_ltr')->textInput() ?>

    <?= $form->field($model, 'can_warning_per')->textInput() ?>

    <?= $form->field($model, 'collection_mode')->textInput() ?>

    <?= $form->field($model, 'collection_quantity_mode')->textInput() ?>

    <?= $form->field($model, 'bmc_collection_quantity_mode')->textInput() ?>

    <?= $form->field($model, 'local_milk_sale_quantity_mode')->textInput() ?>

    <?= $form->field($model, 'sample_milk_quantity_mode')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'default_snf')->textInput() ?>

    <?= $form->field($model, 'default_snf_value')->textInput() ?>

    <?= $form->field($model, 'from_machine_clr')->textInput() ?>

    <?= $form->field($model, 'based_on_local_sale')->textInput() ?>

    <?= $form->field($model, 'no_disp_local_sale')->textInput() ?>

    <?= $form->field($model, 'per_local_sale')->textInput() ?>

    <?= $form->field($model, 'input_clr')->textInput() ?>

    <?= $form->field($model, 'lr1_for_clr')->textInput() ?>

    <?= $form->field($model, 'lr2_for_clr')->textInput() ?>

    <?= $form->field($model, 'ltr_to_kg')->textInput() ?>

    <?= $form->field($model, 'multi_entry_diff_milk_type')->textInput() ?>

    <?= $form->field($model, 'multi_entry_same_milk_type')->textInput() ?>

    <?= $form->field($model, 'no')->textInput() ?>

    <?= $form->field($model, 'no_disp')->textInput() ?>

    <?= $form->field($model, 'sample_milk_size')->textInput() ?>

    <?= $form->field($model, 'seperate_can')->textInput() ?>

    <?= $form->field($model, 'shift_code')->textInput() ?>

    <?= $form->field($model, 'shift_code_disp')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'variation_in_fat')->textInput() ?>

    <?= $form->field($model, 'variation_in_fat_block')->textInput() ?>

    <?= $form->field($model, 'variation_in_qty')->textInput() ?>

    <?= $form->field($model, 'variation_in_qty_block')->textInput() ?>

    <?= $form->field($model, 'variation_in_snf')->textInput() ?>

    <?= $form->field($model, 'variation_in_snf_block')->textInput() ?>

    <?= $form->field($model, 'union_code')->textInput() ?>

    <?= $form->field($model, 'weight_setting')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
