<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = 'Remuneration Stop Payment Process : Step 1';
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$multiple = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'allow_multiselect_in_payment', 'PORTAL') == '1' ? TRUE : FALSE;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>

        <div class="row">
            <div class="col-sm-2" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblremunerationsummary-union_code', 'plant_code', TRUE); ?>
            </div> 
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblremunerationsummary-plant_code', 'mcc_plant_code', TRUE,$multiple); ?>
            </div> 
               <div class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblremunerationsummary-mcc_plant_code', 'bmc_code', 'BMC *',$multiple); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->RemunerationPaymentCycle($model, $form, 'tblremunerationsummary-union_code,tblremunerationsummary-bmc_code', 'payment_cycle_code', 'Payment Cycle'); ?>
            </div>
            <div class="col-sm-2 mt15">
                <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'calculate_milk_recovey'); ?>
            </div>
            <div class="col-sm-2 mt15">
                <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'calculate_other_head'); ?>
            </div>

            <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('Next', $model); ?>   
                    <?= Yii::$app->controls->cancel(); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

