<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
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
        <?php Yii::$app->dropdown->depend_dropdown('product', $model, $form, 'tblaadeshmaster-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product'); ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'sale_rate')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('Y-m-d')); ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'product_mrp')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'distributor_landing_rate')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'sachiv_price')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'member_price')->textInput() ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= $form->field($model, 'is_member_rate', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'commission')->textInput() ?>
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
        dispVsp();
    $('#tblaadeshmaster-is_member_rate').on('change',function(){
        dispVsp();
    });
    function dispVsp(){
        if($('#tblaadeshmaster-is_member_rate').is(':checked')){
            $('.field-tblaadeshmaster-commission').show();
        } else {
            $('.field-tblaadeshmaster-commission').hide();
            $('#tblaadeshmaster-commission').val(0);
        }
    }
    function setMinDate() {
        var id = $('#tblaadeshmaster-product_code').val();
        var code='{$model->aadesh_master_code}';
        var union='{$model->union_code}';
        var odt='{$model->wef_date}';
        if(id!=='' && id!==null){
            $.ajax({
                        type: 'post',
                        url: '" . Url::to(['/product/tbl-aadesh-master/get-min-date']) . "',
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
                                    $('#tblaadeshmaster-wef_date').parent().kvDatepicker('setStartDate',formatDate(dt));
                                //$('#tblaadeshmaster-wef_date').kvDatepicker({startDate:formatDate(dt)});
                                 }else{
                                  $('#tblaadeshmaster-wef_date').parent().kvDatepicker('setStartDate','');
                                 //$('#tblaadeshmaster-wef_date').kvDatepicker({startDate:''});
                                }
                                $('#tblaadeshmaster-wef_date').val(odt);
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
$this->registerJs($script, View::POS_END, 'village-code');
?>
