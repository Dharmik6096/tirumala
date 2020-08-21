<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= $form->field($model, 'transporter_payment_head')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('calc_type', $model, $form, '', 'Type *', false, 'type', false); ?>  
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


