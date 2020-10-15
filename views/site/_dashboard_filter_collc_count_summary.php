<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSearch */
/* @var $form yii\widgets\ActiveForm */

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