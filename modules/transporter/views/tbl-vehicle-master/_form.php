<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
//$model->is_plant=$model->isNewRecord?0:$model->is_plant;
//$nameWarning = 0;
//$codeWarning = 0;
//if (!empty($_POST)) {
//    $nameWarning = $_POST['warning'];
//    $codeWarning = $_POST['code_warning'];
//}
$list = array('0' => 'No', '1' => 'Yes');
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Vehicle Detail</h4>
        </div>
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblvehiclemaster-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('vehicle_type_code', $model, $form, 'form-group col-sm-2', 'Vehicle Type'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('fuel_type_code', $model, $form, 'form-group col-sm-2', 'Fuel Type'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Capacity (LPD)', false, 'capacity_code'); ?>        
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'registration_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'applicable_rto')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d')); ?>
    </div>
    <div class="col-sm-2">
        <?php echo $form->field($model, 'pollution_certificate')->dropdownList($list); ?>
        <?php //= $form->field($model, 'pollution_certificate')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'expiry_date', '', FALSE, date('Y-m-d')); ?>
    </div>
    <div class="col-sm-2">
        <?php echo $form->field($model, 'insurance')->dropdownList($list); ?>
        <?php //= $form->field($model, 'insurance')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'rc_book_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'parsing_no')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'rent')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'average')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('billing_method', $model, $form, 'form-group', $model->getAttributeLabel('billing_method'), false, 'billing_method', false); ?>
    </div>
    <!--<div class="col-sm-2 mt25">-->
        <!--<? Yii::$app->controls->active($model, $form); ?>-->
    <!--</div>-->
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Driver Detail</h4>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'driver_name')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'driver_contact_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'driving_license_number')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'licence_expiry_date', '', FALSE, date('Y-m-d')); ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


