<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\vsp\models\TblHeadLoadTransaction */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Head Load Transaction');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin(['options' => [

                'field-class' => 'form-group col-sm-3'
            ], 'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
            //'labelOptions' => [ 'class' => false],
        ]]);
?>
<div class="panel-body">
    <div class="panel-subheading">
        <h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>

        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <?= $form->field($model, 'from_km', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>

            <?= $form->field($model, 'to_km', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>

            <?= $form->field($model, 'from_qty', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>

            <?= $form->field($model, 'to_qty', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>
            <div class="clearfix"></div>

            <?= $form->field($model, 'value', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['class' => 'form-control number-validate']) ?>
            
        </div>
    </div>
</div> 
<div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?= Yii::$app->controls->save($button, $model); ?>
    <?= Yii::$app->controls->reset(); ?>
    <?= Yii::$app->controls->cancel($model, 'index'); ?>
</div>


<?php ActiveForm::end(); ?>

<?php
$script = '$("#tblheadloadtransaction-from_km").focus();';
//  . '$("#tblheadloadtransaction-from_km").focus().select();';
$this->registerJs($script, View::POS_END, 'from_km');
?>
