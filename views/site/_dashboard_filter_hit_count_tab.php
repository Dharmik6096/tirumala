<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSearch */
/* @var $form yii\widgets\ActiveForm */
$quality_params = ['1' => 'Qty', '2' => 'FAT/SNF', '3' => 'FatKg/SNFKg'];
$model->from_date4 = empty($model->from_date4) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->from_date4;
$model->to_date4 = empty($model->to_date4) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->to_date4;

$model->from_date5 = empty($model->from_date5) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->from_date5;
$model->to_date5 = empty($model->to_date5) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->to_date5;

$range_1_id_from = !empty($hit_range_1_from) ? $hit_range_1_from : false;
$range_1_id_to = !empty($hit_range_1_to) ? $hit_range_1_to : false;

$range_2_id_from = !empty($hit_range_2_from) ? $hit_range_2_from : false;
$range_2_id_to = !empty($hit_range_2_to) ? $hit_range_2_to : false;

$date_range_class = !empty($date_range_class) ? $date_range_class : 'col-sm-3';
//Yii::$app->controls->view_date($date);
?>
<div class="dashboard_controls pt10">
    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'id' => $id
    ]);
    ?>
    <div class="clearfix"></div>
    <div class="col-sm-6">
        <div class="<?= $date_range_class ?>">
            <?php if ($date_range) { ?>
                <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date4', 'to_date4', $range_1_id_from, $range_1_id_to); ?>
            <?php } ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'previous_hit', ['options' => ['class' => 'form-group']])->textInput(['class' => 'form-control'])->input('text',['placeholder' => Yii::t('app', 'No. of Hits')])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="<?= $date_range_class ?>">
            <?php if ($date_range) { ?>
                <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date5', 'to_date5', $range_2_id_from, $range_2_id_to); ?>
            <?php } ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'current_hit', ['options' => ['class' => 'form-group']])->textInput(['class' => 'form-control'])->input('text',['placeholder' => Yii::t('app', 'No. of Hits')])->label(false) ?>
        </div>
    </div>
    <?= Html::activeHiddenInput($model, 'union_code'); ?>
    <?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);    ?>
    <div class="col-sm-3 pt5">
        <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, $id . ' dashboardSearchButton'); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = "
    $(document).ready(function () {
        setCrossTab();
        $('.{$id}').on('click',function(e) {
            e.preventDefault(); 
            setCrossTab();
        });
        
        function setCrossTab(){  
            var from_date_previous = $('#hit_range_1_from').val();
            var to_date_previous = $('#hit_range_1_to').val();
            var from_date_current = $('#hit_range_2_from').val();
            var to_date_current = $('#hit_range_2_to').val();
            var hit_previous = $('#dashboard-previous_hit').val();
            var hit_current = $('#dashboard-current_hit').val();
            var union = $('#dashboard-union_code').val();
            if(from_date_previous != '' && to_date_previous != '' && union != '' && from_date_current != '' && to_date_current != ''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['set-hit-count-tab']) . "',
                    data: {'from_date_previous' : from_date_previous, 'to_date_previous' : to_date_previous, 'from_date_current' : from_date_current, 'to_date_current' : to_date_current, 'union' : union, 'hit_previous': hit_previous, 'hit_current': hit_current} ,
                    success: function(data) {                                        
                        $('#" . $id . "_container').html(data);
                    },
                    error:function(data){

                    }
                });   
            }
            return false;
        }
    });
    
";
$this->registerJs($script, View::POS_READY, $id);
?>