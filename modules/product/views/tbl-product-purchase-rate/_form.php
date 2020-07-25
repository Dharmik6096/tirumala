<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProductSaleRate */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?= $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?php Yii::$app->dropdown->depend_dropdown('product', $model, $form, 'tblproductpurchaserate-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'purchase_rate')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d')); ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
$(document).ready(function(){
    setMinDate();
    
    function setMinDate() {
        var id = $('#tblproductpurchaserate-product_code').val();
        var code='{$model->product_purchase_rate_code}';
        var union='{$model->union_code}';
        var odt='{$model->wef_date}';
        if(id!=='' && id!==null){
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/product/tbl-product-purchase-rate/get-min-date']) . "',
                data: 'id='+id+'&code='+code+'&union='+union,
                success: function(data) {
                    var obj1 = $.parseJSON(data);
                    if (obj1.status == 'success')
                    {
                        var parts =obj1.date.split('-');
                        var dt = new Date(parts[2],parts[1]-1,parts[0]); 
                        var d = new Date();
                        var curdt=formatDate(d);

                        if((code!=='' && code!==null) || !(obj1.date===curdt))
                        {
                            dt.setDate(dt.getDate() + 1);
                        }
                        if (!(obj1.date===curdt)){
                            $.fn.kvDatepicker.defaults.format = 'dd-mm-yyyy';
                            $('#tblproductpurchaserate-wef_date').parent().kvDatepicker('setStartDate',formatDate(dt));
                        } else{
                          $('#tblproductpurchaserate-wef_date').parent().kvDatepicker('setStartDate','');
                        }
                        $('#tblproductpurchaserate-wef_date').val(odt);
                    }
                },
                error:function(data){
                            //alert('Your data has not been submitted..Please try again');
                        }
            }); 
        }
    }
    function formatDate(d) {
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [day, month, year].join('-');
    } 
});
";
$this->registerJs($script, View::POS_END, 'set-purchase-rate-form-data');
?>
