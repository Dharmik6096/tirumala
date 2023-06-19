<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblShiftTime */
/* @var $form yii\widgets\ActiveForm */
//$selected = Yii::$app->session->get('Unions');
//$model->union_code = !empty($selected) ? $selected : $model->union_code;
?>


<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true
        ]);
?>
<div class="row">
    <div class="col-sm-6" id="union">
        <?= $form->field($model, 'description')->textArea() ?>
    </div>
    <!--<div class="clearfix"></div>-->
    <div class="col-sm-6 mt25">
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
<?php ActiveForm::end();
