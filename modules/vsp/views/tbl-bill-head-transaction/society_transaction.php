<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

$this->title = Yii::$app->label->title('create', 'Society Wise Transaction');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">


        <?php
        $form = ActiveForm::begin([
                    'options' => ['id' => 'search-bill-head-form'],
                    'validateOnBlur' => false,
                    
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-3" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
            </div>
            <div class="col-sm-3">
                <?= Yii::$app->dropdown->unionpaymentcycle($model, $form, 'tblbillheaddetail-union_code', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code')); ?>
            </div>
            <div class="col-sm-3">
                <?= Yii::$app->dropdown->unionpaymentcycledcs($model, $form, 'tblbillheaddetail-payment_cycle_code', 'dcs_code', $model->getAttributeLabel('dcs_code')); ?>
            </div>
            <div class="clearfix"></div>
            <div id="appendBillHeadDetails"></div>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?php Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                    <?php Yii::$app->controls->reset(); ?>
                    <?php Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>


    </div>
</div>
<?php
$script = "

$('document').ready(function(){
    setTrnBillHead();
});

function setTrnBillHead(){
    var dcs_code = $('#search-bill-head-form #tblbillheaddetail-dcs_code').val();
    if(dcs_code != ''){       
        var payment_cycle_code = $('#search-bill-head-form #tblbillheaddetail-payment_cycle_code').val();
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/vsp/tbl-bill-head-detail/transaction-bill-head']) . "',
            data: {'dcs_code' : dcs_code, 'payment_cycle_code' : payment_cycle_code},
            beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
            },
            success: function(data) {
                $('#appendBillHeadDetails').html(data);
                $('#loadercontent').hide();
                $('#pageloader').hide();                                                                   
            },
        });
    } else {
        $('#removeBillHeadDetails').remove('');
    }
}

$('#search-bill-head-form #tblbillheaddetail-dcs_code').on('change', function(){
    setTrnBillHead();
});

$(document).on('click', 'button[button=\'save\']',function(){
    $('form#search-bill-head-form').submit();
});

";
$this->registerJs($script, View::POS_END, 'bill-head-detail-form');
?>