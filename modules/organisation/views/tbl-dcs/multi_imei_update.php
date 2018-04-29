<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->title = Yii::$app->label->title('edit', 'IMEI Number');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['action'=>Url::to(['update-imei-number-society'])]); ?>
        <div class="col-sm-3" id="union">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
        </div>
        <div class="col-sm-3">
            <?php Yii::$app->dropdown->union_routes($model, $form, 'tblsocietycodes-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Route'); ?>
        </div>
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?= Yii::$app->controls->save('Next', $model); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    /*$('#tblsocietycodes-route_code').on('change',function(){
            var route = $(this).val();
            var rtname=$('#tblsocietycodes-route_code option:selected').text();
            var vendor = $('#tblsocietycodes-vendor_code').val();
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/organisation/tbl-dcs/load-route-vendor-society']) . "',
                        data: {'route':route,'vendor':vendor},
                        success: function(data) {
                                var obj1 = $.parseJSON(data);
                                if(obj1.found===0)
                                {
                                    bootbox.alert('<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle text-info\'></i></div><br/><div class=\'col-sm-10 padding-left-0 text-danger\'>No society found for '+rtname+'!!</div></div>');
                                    $('#tblsocietycodes-route_code').val('');
                                }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
   });*/
  
";
$this->registerJs($script, View::POS_END, 'union-select');

$script = "var delay=2000;";
$this->registerJs($script, View::POS_HEAD, 'time-loader');
