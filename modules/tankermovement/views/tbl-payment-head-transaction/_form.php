<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Payment Head Transaction');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <?= Yii::$app->dropdown->dropdownStatic('payment_type_transaction_head', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('payment_type'), $readonly, 'payment_type', false); ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('payment_head_code', $model, $form, '', true, $readonly, 'payment_head_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('party_master_code', $model, $form, '', true, $readonly, 'applicable_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'applicable_date'); ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'amount')->textInput() ?>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-2">
            <?= $form->field($model, 'remarks')->textarea() ?>
        </div>

        <div class="col-sm-2 padding_top_20">
            <div class="form-group">
                <?= Yii::$app->controls->save($button, $model); ?>
                <?= Yii::$app->controls->reset(); ?>
                <?= Yii::$app->controls->cancel($model); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>