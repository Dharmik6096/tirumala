<?php

use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\globalmaster\models\TblMilkQualityGrade */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Milk Quality Grade');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$class = ($model->isNewRecord) ? false : true;
$disabled = (($model->isNewRecord) ? '' : 'disabled');
?>
<?php
$form = ActiveForm::begin(['options' => [
                'class' => 'save-form',
                'field-class' => 'form-group col-sm-3 ' . $disabled
            ],
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>
<?php echo $form->errorSummary($model); ?>
<?php //echo $form->errorSummary($modelMilk); ?>
<div class="row">
    <?php Yii::$app->dropdown->federation($model, $form, 'federation_code', 'Federation'); ?>
    <?= Yii::$app->dropdown->union($model, $form, 'tblmilkqualitygrade-federation_code', 'union_code', 'Union'); ?>
    <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'form-group padding-right-5 col-sm-3 ' . $disabled, 'Animal Type', false, 'animal_type_code'); ?>
    <?= $form->field($model, 'grade_name', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true, 'readOnly' => $class]) ?>
    <div class="clearfix"></div>
    <?= $form->field($model, 'ded_percentage', [ 'options' => ['class' => 'form-group col-sm-3 number-validate']])->textInput(['readOnly' => $class]) ?>
    <?= $form->field($model, 'name', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true, 'readOnly' => $class]) ?>

    <?=
    $this->render('_milk_type', [
        'milkType' => $milkType, 'modelMilk' => $modelMilk, 'form' => $form, 'model' => $model
    ])
    ?>
</div>
<div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?= Yii::$app->controls->save($button, $model); ?>
    <?= Yii::$app->controls->reset(); ?>
    <?= Yii::$app->controls->cancel($model, 'index'); ?>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $('#tblmilkqualitygrade-animal_type_code').on('change',function(){
        $.pjax.reload({container: '#milk-quality-list'});
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>
