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
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($summary_model); ?>
<?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>
<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <?= Html::activeHiddenInput($model, 'is_plant') ?>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('plant', $model, $form, 'tblmccplant-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Plant', '', $disabled); ?>
    </div>
    <div class="col-sm-3 number-validate">  
        <?= $form->field($model, 'mcc_plant_code')->textInput(['readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <!--    <div class="col-sm-3">
            <? = $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <? = $form->field($model, 'local_contact_person_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <? = $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <? = $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>
        </div>-->
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('capacity', $model, $form, '', 'Capacity (LPD)', false, 'capacity'); ?>        
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->valid_date($model, $form, 'valid_from'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'milk_type_code')->listBox($milkType['value'], ['multiple' => 'multiple', 'size' => '10', 'options' => $milkType['selected']]); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->state($model, $form, 'state_code', 'State', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->uniondistrict($model, $form, 'tblmccplant-union_code,tblmccplant-state_code', 'district_code', 'District', FALSE, $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('sub_district_code', $model, $form, 'tblmccplant-district_code', 'form-group col-sm-4', 'Sub District', 'sub_district_code', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('village_code', $model, $form, 'tblmccplant-sub_district_code', 'form-group col-sm-4', 'Village', '', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->depend_dropdown('hamlet_code', $model, $form, 'tblmccplant-village_code', 'form-group col-sm-4', 'Hamlet'); ?>
    </div>
    <?= Html::hiddenInput('from_plant', 0, ['id' => 'mcc']); ?>

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
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
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