<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSearch */
/* @var $form yii\widgets\ActiveForm */
$quality_params = ['1' => 'Qty', '2' => 'FAT/SNF', '3' => 'FatKg/SNFKg'];
$model->from_date = empty($model->from_date) ? Yii::$app->controls->view_date(date('Y-m-d',strtotime('-6 days'))) : $model->from_date;
$model->to_date = empty($model->to_date) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->to_date;
$model->from_date2 = empty($model->from_date2) ? Yii::$app->controls->view_date(date('Y-m-d',strtotime('-13 days'))) : $model->from_date2;
$model->to_date2 = empty($model->to_date2) ? Yii::$app->controls->view_date(date('Y-m-d',strtotime('-7 days'))) : $model->to_date2;
$model->shift = empty($model->shift) ? 1 : $model->shift;
$id1=  !empty($range_id1)?$range_id1:false;
$id2=  !empty($range_id2)?$range_id2:false;
//Yii::$app->controls->view_date($date);
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'id' => $id
        ]);
?>
<div class="col-sm-3">
    <?php if ($date_range) { ?>
        <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date', 'to_date',$id1,$id2); ?>
    <?php } else { ?>
        <?= Yii::$app->controls->date($model, $form, 'date', 'form-group col-sm-2', true, false, false, false,$id1); ?>
    <?php } ?>
</div>

<?php if ($range2) { ?>
<div class="col-sm-3">
    <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date2', 'to_date2'); ?>
</div>
<?php } ?>

<?php if ($shift) { ?>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift'); ?>
    </div>
<?php } ?>
<?php if (empty($hide_param)) { ?>
<div class="col-sm-3">
    <?= $form->field($model, 'qlt_param')->dropDownList($quality_params)->label(false); ?>
</div>
<?php } ?>
<?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);   ?>
<div class="col-sm-2">
    <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, $id); ?>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    barChart('{$container}','{$title}',[],[]);
    drawChart('{$id}','{$container}','{$url}','{$type}');
    $('.{$id}').on('click',function(e) {
        e.preventDefault();
        drawChart('{$id}','{$container}','{$url}','{$type}');        
        return false;
    });
    
";
$this->registerJs($script, View::POS_READY, $id);
?>