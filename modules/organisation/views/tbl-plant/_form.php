<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlant */
/* @var $form yii\widgets\ActiveForm */
$summary_model = $type == 'create' ? [$model, $contactDetails] : $model;
$readonly = $type == 'create' ? FALSE : TRUE;
$class = ($type == 'create' || $model->is_virtual_plant != 1) ? '' : 'no_pointer';
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
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
            <h4 class="theme-box-heading">Plant Details</h4>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
        </div>
        <?php
        $keyPattern = Yii::$app->general->getKeyPattern('tbl_plant');
        if (!empty($keyPattern)) {
            ?>
            <?php if (!$readonly && $keyPattern['ex_code_auto'] == 0) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'plant_code_ex')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
            <?php if (!$readonly && $keyPattern['ref_code_type'] == 2) { ?>
                <div class="col-sm-2 number-validate">  
                    <?= $form->field($model, 'ref_code')->textInput(['readonly' => $readonly]) ?>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="col-sm-2">  
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
        <div class="col-sm-2"> 
            <?= $form->field($model, 'local_name')->textInput() ?>
        </div>
        <!--    <div class="col-sm-2"> 
                <? = $form->field($model, 'contact_person')->textInput() ?>
            </div>
            <div class="col-sm-2"> 
                <? = $form->field($model, 'local_contact_person_name')->textInput() ?>
            </div>
            <div class="col-sm-2">  
                <? = $form->field($model, 'email')->textInput() ?>
            </div>
            <div class="col-sm-2"> 
                <? = $form->field($model, 'mobile_no')->textInput() ?>
            </div>-->
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Capacity (LPD)', false, 'capacity'); ?>        
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
        </div>
        <div class="col-sm-2"> 
            <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
        </div>
        <div class="col-sm-2"> 
            <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblplant-union_code,tblplant-state_code', 'district_code', Yii::t('app', 'District'), FALSE); ?>
        </div>
        <div class="col-sm-2"> 
            <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblplant-district_code', 'form-group col-sm-2', Yii::t('app', 'Sub District'), 'sub_district_code'); ?>
        </div>
        <div class="col-sm-2"> 
            <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblplant-sub_district_code', 'form-group col-sm-2', Yii::t('app', 'Village'), ''); ?>
        </div>
        <div class="col-sm-2">  
            <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblplant-village_code', 'form-group col-sm-2', Yii::t('app', 'Hamlet')); ?>
        </div>
        <div class="col-sm-2">  
            <?= $form->field($model, 'sap_vendor_code')->textInput() ?>
        </div>
        <div class="col-sm-2"> 
            <?= $form->field($model, 'description')->textarea() ?>
        </div>
        <div class="col-sm-2 mt10">
            <?= $form->field($model, 'is_virtual_plant', ['checkboxTemplate' => "<div class='checkbox " . $class . "''>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        </div>
        <?php if ($type == 'create') { ?>
            <div class="clearfix"></div>
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                <h4 class="theme-box-heading">Contact Details</h4>
            </div>
            <?=
            $this->render('../../../details/views/tbl-contact-details/_form', [
                'model' => $contactDetails,
                'form' => $form
            ])
            ?>
            <div class="clearfix"></div>
        <?php } ?>


        <div class="col-sm-2 mt10">
            <?= Yii::$app->controls->active($model, $form); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">  
            <?php // Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
