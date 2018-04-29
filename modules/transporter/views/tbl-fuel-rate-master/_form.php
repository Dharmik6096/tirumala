<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
//$model->is_plant=$model->isNewRecord?0:$model->is_plant;
//$nameWarning = 0;
//$codeWarning = 0;
//if (!empty($_POST)) {
//    $nameWarning = $_POST['warning'];
//    $codeWarning = $_POST['code_warning'];
//}
$list = array('0' => 'No', '1' => 'Yes');
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('fuel_type_code', $model, $form, 'form-group col-sm-3', 'Fuel Type',$readonly); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'rate')->textInput() ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d'),$readonly); ?>
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


