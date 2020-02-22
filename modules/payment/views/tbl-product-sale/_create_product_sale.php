<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('create', 'Product Sale');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => FALSE,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-2" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblproductsale-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
            </div> 
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblproductsale-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
            </div>      
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblproductsale-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
            </div>
            <!--<div class="col-sm-3">-->                                             
            <?php // Yii::$app->dropdown->bmc_society($model, $form, 'tblproductsale-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), FALSE, '', FALSE, TRUE); ?>         
            <!--</div>--> 

            <div class="col-sm-2">
                <?= Yii::$app->dropdown->customer_type($model, $form, 'tblproductsale-bmc_code', 'customer_type', TRUE, FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->customer_code($model, $form, 'tblproductsale-bmc_code,tblproductsale-customer_type', 'customer_code', TRUE, FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'sale_date_time', '', true); ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdownStatic('payment_mode', $model, $form, 'form-group', $model->getAttributeLabel('type'), false, 'type', false); ?>
            </div>
            <div class="col-sm-2">
                <?php Yii::$app->dropdown->depend_dropdown('product', $detailModel, $form, 'tblproductsale-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($detailModel, 'rate')->textInput(['readOnly' => true]) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($detailModel, 'qty')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($detailModel, 'amount')->textInput(['readOnly' => true]) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($detailModel, 'discount')->textInput() ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($detailModel, 'amount_due')->textInput(['readOnly' => true]) ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
