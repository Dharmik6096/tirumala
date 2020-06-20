<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSearch */
/* @var $form yii\widgets\ActiveForm */
$quality_params = ['1' => 'Qty', '2' => 'FAT/SNF', '3' => 'FatKg/SNFKg'];
$model->from_date3 = empty($model->from_date3) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->from_date3;
$model->to_date3 = empty($model->to_date3) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->to_date3;

$id1 = !empty($range_id1) ? $range_id1 : false;
$id2 = !empty($range_id2) ? $range_id2 : false;
$date_range_class = !empty($date_range_class) ? $date_range_class : 'col-sm-3';
//Yii::$app->controls->view_date($date);
?>
<div class="dashboard_controls">
<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'id' => $id
        ]);
?>
<div class="<?= $date_range_class ?>">
    <?php if ($date_range) { ?>
        <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date3', 'to_date3', $id1, $id2); ?>
    <?php } ?>
<?= Html::activeHiddenInput($model, 'union_code'); ?>
</div>

    <?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);    ?>
<div class="col-sm-2 ml35">
<?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, $id); ?>
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
            var from_date = $('#cross_tab_dt1').val();
            var to_date = $('#cross_tab_dt2').val();
            var union = $('#dashboard-union_code').val();
            if(from_date != '' && to_date != '' && union != ''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['set-cross-tab']) . "',
                    data: {'from_date' : from_date, 'to_date' : to_date, 'union' : union} ,
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