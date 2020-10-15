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
<div class="dashboard_controls">
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
            var blockDataString = $('#collapse1 form').serialize();
            var id= '".$id."';
            var union= $('#dashboard-union_code').val();
            var mcc= $('#dashboard-mcc_code').val();
            $.ajax({
                type: 'post',
                url:'" . Url::to(['set-collection-count-summary']) . "',
                data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc,
                success: function(data) {                                        
                    $('#" . $id . "_container').html(data);
                },
                error:function(data){

                }
            });   
            return false;
        }
    });
    
";
$this->registerJs($script, View::POS_READY, $id);
?>