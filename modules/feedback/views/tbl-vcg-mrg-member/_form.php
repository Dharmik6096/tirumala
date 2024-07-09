<?php
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
$isVcgMrgMeetingApprovalRequired = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'is_vcg_mrg_meeting_approval_required', 'PORTAL') == 1 ? TRUE : FALSE;
?>
<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'end_date'); ?>
    </div>
    <?php
    if($isVcgMrgMeetingApprovalRequired){ ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdownStatic('vcg_mrg_member_status', $model, $form, 'form-group', $model->getAttributeLabel('status'), false, 'status', false); ?>
        </div>
    <?php
    } ?>
    <div class="col-sm-2">
        <?= $form->field($model, 'remark')->textarea(['maxlength' => true]) ?>
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

