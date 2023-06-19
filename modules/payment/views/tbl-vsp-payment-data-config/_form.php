<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblDcsPaymentCycle */
/* @var $form yii\widgets\ActiveForm */


$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'no_pointer';
//$divert = !isset($divert) ? FALSE : $divert;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
//            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?= $form->errorSummary($model); ?>
<div class="row">   

    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvsppaymentdataconfig-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $readonly); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvsppaymentdataconfig-plant_code', 'mcc_plant_code', 'MCC', false, '', $readonly, false, ''); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppaymentdataconfig-mcc_plant_code', 'bmc_code', 'BMC', false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblvsppaymentdataconfig-bmc_code', 'dcs_code', 'DCS', false, '', $readonly); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', '', false, false, false, true, false, '', FALSE); ?>
    </div>  
    <div class="col-sm-2 shift   <?= $class ?>">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', $model->getAttributeLabel('shift_code'), false, 'shift_code'); ?>
    </div> 
    <!--    <div class="col-sm-2 mt35">
            <? = Yii::$app->controls->active($model, $form); ?>
        </div>-->
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


