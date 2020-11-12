<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-filter mt10">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code'); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_mcc($model, $form, 'tblftptxnlogsearch-f_union_code', 'f_mcc_code', FALSE); ?>
    </div>
    <div class="col-sm-2 height100">
        <?php
        echo Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, false);
        ?>
    </div>    
    <div class="col-sm-2 shift height100">
        <?php
        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-2 form-group', false, false, 'from_shift');
        ?>
    </div> 

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('sap_file_type', $model, $form, 'form-group padding-right-5', false, false, 'module_name') ?> 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('sap_file_status', $model, $form, 'form-group padding-right-5', false, false, 'status') ?> 
    </div>
    <div class="col-sm-2">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
