<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$title = Yii::$app->label->title($type, 'Alert Rule Mapping');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin([
            'id' => 'role-form',
            'validateOnBlur' => false,
        ])
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>    
    </div>
    <div class="col-sm-2">
        <?= Html::hiddenInput('is_active', '1', ['id' => 'is_active']); ?>
        <?php Yii::$app->dropdown->depend_dropdown('rule_code', $model, $form, 'tblalertrulemapping-union_code,is_active', 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('rule_code')); ?>
    </div>
</div>
<div class="col-md-12 padding_10_0 theme-box view-subtitle">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
        <h4 class="theme-box-heading">Department</h4>
    </div>
    <div class="row">
        <?php foreach ($department as $key => $depart) { ?>
            <div class="col-sm-2">
                <?=
                Html::checkbox('TblAlertRuleMapping[department_id][]', false, [
                    'label' => $depart['department'], 'value' => $depart['department_id'], 'id' => 'tblalertrulemapping-' . $key . '-department_id',
                ]);
                ?>
            </div>
        <?php } ?>
    </div>
</div>
<div class="col-md-12 padding_10_0 theme-box view-subtitle">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
        <h4 class="theme-box-heading">Organization Type</h4>
    </div>
    <div class="row">
        <?php
        $organization_types = Yii::$app->general->getStaticData($model);
        foreach ($organization_types as $key => $org) {
            ?>
            <div class="col-sm-2">
                <?=
                Html::checkbox('TblAlertRuleMapping[organization_type][]', false, [
                    'label' => $key, 'value' => $org, 'id' => 'tblalertrulemapping-' . $key . '-organization_type',
                ]);
                ?>
            </div>
        <?php } ?>
    </div>
</div>
<div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>

<?php ActiveForm::end() ?>
