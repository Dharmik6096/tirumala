<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$depend = 'transitrecovery';
?>

<div class="tbl-milk-collection-search">
    <?php
    $form = ActiveForm::begin([
                'id' => 'recovery_search',
                'method' => 'get',
                'validateOnSubmit'=>true,
    ]);
    ?>   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, $depend . '-union_code', 'plant_code', 'Plant'); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, $depend . '-plant_code', 'mcc_plant_code', 'MCC'); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $depend . '-mcc_plant_code', 'bmc_code', 'BMC'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?php
        echo Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false);
        ?>
    </div>    
    <div class="col-sm-2 shift height100">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('from_shift'), false, 'from_shift');
        ?>
    </div>  
    <div class="col-sm-2">
        <?php
        echo Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false);
        ?>
    </div>    
    <div class="col-sm-2 shift">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('to_shift'), false, 'to_shift');
        ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('order_on', $model, $form, 'form-group', $model->getAttributeLabel('order_on'), false, 'order_on', false); ?>
    </div>
    <?php // if (empty($dataProvider->getModels())) { ?>
    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php // } ?>

    <?php ActiveForm::end(); ?>
</div>


<?php
$script = "
$('#recovery_search').submit(function(e){
    // e.preventDefault();
    e.stopImmediatePropagation();
});";

$this->registerJs($script, View::POS_END, 'transit-recovery-search');
?>