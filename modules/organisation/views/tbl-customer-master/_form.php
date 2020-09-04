<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblcustomermaster-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>  
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblcustomermaster-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblcustomermaster-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-3 DCS">
        <?= Yii::$app->dropdown->all_routes($model, $form, 'tblcustomermaster-plant_code,tblcustomermaster-mcc_plant_code,tblcustomermaster-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3 ">
        <?= Yii::$app->dropdown->dropdown('customer_type', $model, $form, 'form-group col-sm-3', $model->getAttributeLabel('customer_type'), $readonly); ?>
    </div>
    <?php
    $keyPattern = Yii::$app->general->getKeyPattern('tbl_customer_master');
    if (!empty($keyPattern)) {
        ?>
        <?php if ($readonly || $keyPattern['ex_code_auto'] == 0) { ?>
            <div class="col-sm-3"> 
                <?= $form->field($model, 'customer_code_ex')->textInput() ?>
            </div>
        <?php } ?>
        <?php if ($readonly || $keyPattern['ref_code_type'] == 2) { ?>
            <div class="col-sm-3 number-validate">  
                <?= $form->field($model, 'ref_code')->textInput() ?>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'customer_name')->textInput() ?>
    </div>

    <div class="col-sm-3">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'gst_no')->textInput() ?>
    </div>
    <div class='pull-left col-sm-6'>
        <?= Yii::t('app', 'Allow multiple collection entry for shift') ?><br/>
        <?= $form->field($model, 'same_milk_type', ['options' => ['class' => 'form-group col-sm-3 padding-left-0'], 'checkboxTemplate' => "<div class='checkbox' >{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(); ?>
        <?= $form->field($model, 'diff_milk_type', ['options' => ['class' => 'form-group col-sm-4'], 'checkboxTemplate' => '<div class="checkbox" >{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}'])->checkbox(); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local_textarea($model, $form, 'local_address'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->state($model, $form, 'state_code', $model->getAttributeLabel('state_code'), FALSE); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('district_code', $model, $form, 'tblcustomermaster-state_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('district_code'), 'district_code', FALSE); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblcustomermaster-district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('sub_district_code'), 'sub_district_code', FALSE); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblcustomermaster-sub_district_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('village_code'), 'village_code', FALSE); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblcustomermaster-village_code', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('hamlet_code'), 'hamlet_code', FALSE); ?>
    </div>
    <div class="clearfix"></div>
    <?php if ($type == 'create') { ?>
        <div class="col-sm-12">
            <p class="form-subtitle">Contact Details</p>
            <hr class="hr10">
        </div>
        <?=
        $this->render('../../../details/views/tbl-contact-details/_form', [
            'model' => $contactDetails,
            'form' => $form
        ])
        ?>

        <div class="clearfix"></div>

        <div class="col-sm-12">
            <p class="form-subtitle">Bank Details</p>
            <hr class="hr10">
        </div>
        <?=
        $this->render('../../../details/views/tbl-bank-details/_form', [
            'model' => $bankDetails,
            'form' => $form,
            'dist_field' => 'tblcustomermaster-district_code'
        ])
        ?>
    <?php } ?>

</div>

<div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>