<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use app\modules\dcsaccounting\models\TblFinancialYear;

$class = $type == 'edit' ? 'disabled' : '';
$readonly = $type == 'edit' ? false : true;

$form = ActiveForm::begin([
            'options' => ['id' => 'staff-add-ded-form'],
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('staff_member_code', $model, $form, 'tblstaffadditiondeduction-union_code', 'form-group col-sm-3 ' . $class, Yii::t('app', 'Name')); ?>
    </div>
    <?php if ($type == 'create') { ?>
        <?php $model->tr_date = empty($model->tr_date) ? date('Y-m-d') : NULL; ?>
    <?php } ?>
    <?php
    $fyear = new TblFinancialYear();
    $fyear->code = Yii::$app->session->get('financialYear');
    $currentfy = $fyear->getFinancialYear();
    $min = !empty($currentfy->starting_date) ? $currentfy->starting_date : '';
    $max = !empty($currentfy->ending_date) ? $currentfy->ending_date : '';
    ?>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'tr_date', 'form-group col-sm-3 ' . $class, true, $min, false, TRUE, FALSE, $max); ?>
    </div>

    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdownStatic('type', $model, $form, 'form-group ' . $class, $model->getAttributeLabel('type')); ?>
    </div>
    <div class="col-sm-3">
        <?php
        $model->app_from_date = !empty($model->app_from_date) ? date('m-Y', strtotime($model->app_from_date)) : NULL;
        ?>
        <?=
        $form->field($model, 'app_from_date')->widget(\yii\widgets\MaskedInput::className(), ['options' => ['class' => 'form-control'],
            'mask' => '99-9999',])
        ?> 
    </div>           
    <?= $form->field($model, 'amount', ['options' => ['class' => 'form-group col-sm-3']])->textInput() ?>
    <?= $form->field($model, 'installment_no', ['options' => ['class' => 'form-group col-sm-3']])->textInput() ?>
    <?= $form->field($model, 'remark', ['options' => ['class' => 'form-group col-sm-3']])->textarea() ?>

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

