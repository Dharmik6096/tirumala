<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$depend = 'tblpaymentcycleapplicabilitysearch';
?>

<div class="tbl-milk-collection-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', Yii::t('app', 'Union')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmcDropdown($model, $form, $depend . '-union_code', 'applicable_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <?= Yii::$app->dropdown->dropdownStatic('check_for', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', $model->getAttributeLabel('check_for'), FALSE, 'check_for', false) ?> 
    <?= Yii::$app->dropdown->dropdownStatic('data_status', $model, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', $model->getAttributeLabel('data_status'), FALSE, 'data_status', false) ?> 

    <div class="clearfix"></div>
    <?php // if (empty($dataProvider->getModels())) { ?>
    <div class="col-sm-2 mt20">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php // } ?>

    <?php ActiveForm::end(); ?>
</div>
