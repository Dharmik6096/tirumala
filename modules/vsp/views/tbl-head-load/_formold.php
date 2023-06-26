<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\vsp\models\TblHeadLoad */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Head Load');
$this->title = Yii::t('app', $title);
?>

    <?php $form = ActiveForm::begin(['options' => [

                'field-class' => 'form-group col-sm-3'
            ],'validateOnBlur' => FALSE,
        'validateOnChange'=>FALSE,
        'enableClientValidation'=>true,
        'validateOnSubmit'=>true,
        'fieldConfig' => [
                //'labelOptions' => [ 'class' => false],
    ]]); ?>
    <div class="panel-body">
        <div class="panel-subheading">
            <h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>

            <?php echo $form->errorSummary($model); ?>
            <div class="row">
                <?= Yii::$app->dropdown->dropdown('criteria_type_code', $model, $form, 'form-group col-sm-4', 'Criteria Type'); ?>        

                <?= $form->field($model, 'criteria_description', ['options' => ['class' => 'form-group col-sm-8']])->textArea() ?>        
           
                <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
            </div>

        </div>
    </div>      
    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Yii::$app->controls->save('Next',$model);  ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model,'index'); ?>
    </div>


    <?php ActiveForm::end(); ?>
