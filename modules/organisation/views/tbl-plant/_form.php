<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlant */
/* @var $form yii\widgets\ActiveForm */
$summary_model = $type == 'create' ? [$model, $contactDetails] : $model;
$readonly = $type == 'create' ? FALSE : TRUE;
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
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-3 number-validate">  
        <?= $form->field($model, 'plant_code')->textInput(['readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-3">  
        <?= $form->field($model, 'name')->textInput() ?>
    </div>
    <div class="col-sm-3"> 
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <!--    <div class="col-sm-3"> 
            <? = $form->field($model, 'contact_person')->textInput() ?>
        </div>
        <div class="col-sm-3"> 
            <? = $form->field($model, 'local_contact_person_name')->textInput() ?>
        </div>
        <div class="col-sm-3">  
            <? = $form->field($model, 'email')->textInput() ?>
        </div>
        <div class="col-sm-3"> 
            <? = $form->field($model, 'mobile_no')->textInput() ?>
        </div>-->
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Capacity (LPD)', false, 'capacity'); ?>        
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3"> 
        <?= $form->field($model, 'description')->textarea() ?>
    </div>
    <div class="col-sm-3"> 
        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
    </div>
    <div class="col-sm-3"> 
        <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblplant-union_code,tblplant-state_code', 'district_code', 'District', FALSE, $readonly); ?>
    </div>
    <div class="col-sm-3"> 
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblplant-district_code', 'form-group col-sm-4', 'Sub District', 'sub_district_code', $readonly); ?>
    </div>
    <div class="col-sm-3"> 
        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblplant-sub_district_code', 'form-group col-sm-4', 'Village', '', $readonly); ?>
    </div>
    <div class="col-sm-3">  
        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblplant-village_code', 'form-group col-sm-4', 'Hamlet'); ?>
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
    <?php } ?>

    <div class="clearfix"></div>

    <div class="col-sm-3">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">  
            <?php // Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
