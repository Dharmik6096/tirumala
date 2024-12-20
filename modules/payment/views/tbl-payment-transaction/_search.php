<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$multiple = isset($multiple) ? TRUE : FALSE;
?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
        'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblpaymenttransactionsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblpaymenttransactionsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblpaymenttransactionsearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>
    <div class="col-sm-1 ">
        <?php
            $where = json_encode(['is_billing' => 1]);
            $notInArr = json_encode([]);
            echo Html::hiddenInput('customer_type_depends', $where, ['id' => 'customer_type_depends']);
            echo Html::hiddenInput('customer_type_depends_not_in', $notInArr, ['id' => 'customer_type_depends_not_in']);
            echo Yii::$app->dropdown->customerType($model, $form, 'tblpaymenttransactionsearch-union_code,customer_type_depends,customer_type_depends_not_in', 'type', $model->getAttributeLabel('type'), FALSE, FALSE);
        ?>
    </div>
    <div class="col-sm-1">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-1">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>