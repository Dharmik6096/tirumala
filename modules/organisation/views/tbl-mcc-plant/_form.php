<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$summary_model = $type == 'create' ? [$model, $contactDetails] : $model;
$readonly = $type == 'create' ? FALSE : TRUE;
$model->is_plant = $model->isNewRecord ? 0 : $model->is_plant;
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$disabled = ($model->is_plant == 1) ? TRUE : FALSE;
$milkType = $model->getMilkTypes();
?>


<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">
    <div class="col-md-12 padding_10_0 theme-box ">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'MCC Details') ?></h4>
        </div>
        <div class="col-sm-2" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <?= Html::activeHiddenInput($model, 'is_plant') ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmccplant-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, '', $readonly); ?>
            <!--Yii::$app->dropdown->depend_dropdown('plant', $model, $form, 'tblmccplant-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Plant', '', $readonly); ?>-->
        </div>
        <?php
        $keyPattern = Yii::$app->general->getKeyPattern('tbl_mcc_plant');
        if (!empty($keyPattern)) {
            ?>
            <?php if (!$readonly && $keyPattern['ex_code_auto'] == 0) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'mcc_plant_code_ex')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
            <?php if (!$readonly && $keyPattern['ref_code_type'] == 2) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'ref_code')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->local($model, $form); ?>
        </div>
        <!--    <div class="col-sm-2">
                <? = $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-2">
                <? = $form->field($model, 'local_contact_person_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-2">
                <? = $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-2">
                <? = $form->field($model, 'mobile_no')->textInput(['maxlength' => true,'class' => 'form-control check_mobile_length']) ?>
            </div>-->
        <div class="col-sm-2">

            <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Capacity (LPD)', false, 'capacity'); ?>        
        </div>
        <div class="col-sm-2">

            <?= $form->field($model, 'milk_type_code')->listBox($milkType['value'], ['multiple' => 'multiple', 'size' => '10', 'options' => $milkType['selected']]); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
        </div>
        <div class="col-sm-2 hidden-for-specific-client">  
            <?= $form->field($model, 'gst_no')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('is_type', $model, $form, '', 'Has Min Qty Limit', false, 'has_min_qty_limit', false); ?>    
        </div>
        <div class="col-sm-2">  
            <?= $form->field($model, 'min_qty_limit')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblmccplant-union_code,tblmccplant-state_code', 'district_code', Yii::t('app', 'District'), FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblmccplant-district_code', 'form-group col-sm-4', Yii::t('app', 'Sub District'), 'sub_district_code'); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblmccplant-sub_district_code', 'form-group col-sm-4', Yii::t('app', 'Village'), ''); ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblmccplant-village_code', 'form-group col-sm-4', Yii::t('app', 'Hamlet')); ?>
        </div>
        <div class="col-sm-2">  
            <?= $form->field($model, 'sap_vendor_code')->textInput() ?>
        </div>
        <?= Html::hiddenInput('from_plant', 0, ['id' => 'mcc']); ?>
    </div>
    <?php if ($type == 'create') { ?>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Contact Details</h4>
        </div>
        <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form,
            'mail_info' => TRUE
        ])
        ?>

    <?php } ?>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_weight_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_quality_manual', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'recovery_validate', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
    </div>
    <div class="clearfix"></div>
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