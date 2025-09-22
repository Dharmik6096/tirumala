<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMember */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row theme_border_left theme_border_right theme_border_bottom">

    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblforcesyncrequest-union_code', '', 'Society', '', $readonly); ?>
    </div>
    <div class="col-sm-2 rtpl_validate create_fields">
        <?= Yii::$app->controls->date($model, $form, 'from_datetime', '', date('Y-m-d'), false, $readonly, true); ?>
    </div>
    <div class="col-sm-2 shift rtpl_validate create_fields">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'from_shift', true, $readonly, 'from_shift'); ?>
    </div>
    <div class="col-sm-2 rtpl_validate create_fields">
        <?= Yii::$app->controls->date($model, $form, 'to_datetime', '', date('Y-m-d'), false, $readonly, true); ?>
    </div>
    <div class="col-sm-2 shift rtpl_validate create_fields">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'to_shift', true, $readonly, 'to_shift'); ?>
    </div>
    <div class="col-sm-2 shift rtpl_validate create_fields">
        <?= Yii::$app->dropdown->dropdown('forceSyncTables', $model, $form, 'table_name', true, $readonly, 'table_name'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 margin-top-10 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
            <?php //Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
            ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>