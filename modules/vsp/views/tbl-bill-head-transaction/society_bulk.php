<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('create', 'Head Wise Bulk Entry');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">


        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => false,
                    
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-3" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-3">
                <?= Yii::$app->dropdown->unionpaymentcycle($model, $form, 'tblbillheaddetail-union_code', 'payment_cycle_code', 'Payment Cycle'); ?>
            </div>
            <div class="col-sm-3">
                <?= Yii::$app->dropdown->bill_head($model, $form, 'tblbillheaddetail-union_code','bill_head_code', 'Bill Head', 'U'); ?>       
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'amount')->textInput() ?>
            </div>
            <?= Html::input('hidden', 'count', '0', ['class' => 'form-control','id'=>'dcs_count']) ?>
            <div class="col-sm-12">
                <div class="app-check-list">
                    <h4>Society List</h4>
                    <div class="form-group">
                        <div class="checkbox app-check-all">
                            <label class="route-text">
                                <?= Html::checkbox('checkall', false, ['id' => 'checkAll', 'class' => 'route-checkbox']) ?>
                                <label for="checkAll">Check All Societies</label>
                            </label>
                        </div>
                    </div>
                    <div id="dcs-wrap">
                        <div id="society-list" class="row">

                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = "

    $('#checkAll').on('click',function(){
        $('#society-list').find('.route-checkbox').prop('checked', this.checked);
    });
    
    $('#tblbillheaddetail-bill_head_code').on('change',function(){
         $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/vsp/tbl-bill-head-detail/society-list']) . "',
                        data: {payment_cycle_code:$('#tblbillheaddetail-payment_cycle_code').val(), bill_head_code:$(this).val()},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $('#society-list').empty();
                                var i=1;
                                $.each(obj1.data, function(index, value) {
                                    $('#society-list').append('<div class=\"col-sm-4 dcs-checklist checklist\" id=\"nd-'+index+'\"><div class=\"checkbox\"><input type=\"checkbox\" class=\"route-checkbox\" name=\"dcs_code[]\" value=\"'+index+'\" id=\"'+('dcs'+i)+'\"><label class=\"route-text\" for=\"'+('dcs'+i)+'\">'+value+'</label></div></div>');
                                    i++;
                                });
                                $('#dcs_count').val(i);

                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });


";
$this->registerJs($script, View::POS_END, 'bill-head-detail-form');
?>