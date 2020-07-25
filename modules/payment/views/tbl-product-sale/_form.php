<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\View;
use yii\helpers\Url;

$url = Url::to(['/payment/tbl-product-sale-details/create']);
$dcs_url= Url::to(['/organisation/tbl-dcs/load-societies']);
/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblProductSale */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblproductsale-union_code', '', 'Society'); ?>
        <?php //Yii::$app->dropdown->depend_select2($model, $form,'dcs_code',$dcs_url, 'tblproductsale-union_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblproductsale-dcs_code', '', 'Member'); ?>
    </div>
    <!--<div class="col-sm-4">
    <?php /* AutoComplete::widget([
      'model' => $model,
      'attribute' => 'dcs_code',
      'clientOptions' => [
      'source' => ['USA', 'RUS'],
      ],
      ]); */ ?>
    </div>
    <div class="col-sm-4">
    <?php /* AutoComplete::widget([
      'model' => $model,
      'attribute' => 'member_code',
      'clientOptions' => [
      'source' => ['01'=>'USA', '02'=>'RUS'],
      ],
      ]); */ ?>
    </div>-->
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Html::button($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['id' => 'nxt', 'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $(document).ready(function() {
        localStorage.removeItem('sale_data');
   });
   $('#nxt').on('click',function(){
            var saleObj=new Object();
            saleObj.union_code=$('#tblproductsale-union_code').val();
            saleObj.dcs_code=$('#tblproductsale-dcs_code').val();
            saleObj.member_code= $('#tblproductsale-member_code').val();
            if(saleObj.member_code == ''){ return false;}
            //console.log(saleObj);
            var sale_data=JSON.stringify(saleObj);
            localStorage.setItem('sale_data', sale_data);
            localStorage.setItem('test', 'test');
            //console.log(localStorage.getItem('sale_data'));
            window.location.href = '{$url}';
        
        });
//    function loadSociety()
//    {
//        var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
//         $.ajax({
//                type: 'post',
//                url: '" . Yii::$app->request->baseUrl . "/payment/tbl-product-sale/get-society',
//                data: {union_code: '', _csrf : csrfToken},
//                success: function(data) {
//                        var result = $.parseJSON(data);
//                        var obj= result.data;
//                        var arr=[];
//                        $.each(obj, function(k, v) {
//                            //display the key and value pair
//                            arr[k]=v;
//                        });
//                        //var arr =  $.map(obj, function(key,val) { return key+':'+val });
//                        console.log(arr);
//                        $('#tblproductsale-dcs_code').autocomplete({
//                            source: arr
//                        });
//                },
//                error:function(data){
//                            //alert('Your data has not been submitted..Please try again');
//                        }
//            });    
//        
//    }
//   $('#tblproductsale-dcs_code').autocomplete({
//        select: function( event, ui ) {
//            var dcs = ui.item.id;
//            console.log(ui.item);
//               var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
//         $.ajax({
//                type: 'post',
//                url: '" . Yii::$app->request->baseUrl . "/payment/tbl-product-sale/get-member',
//                data: 'society='+dcs+' _csrf='+csrfToken,
//                success: function(data) {
//                        var result = $.parseJSON(data);
//                        var obj= result.data;
//                        var arr = Object.keys(obj).map(function(k) { return obj[k] });
//                        $('tblproductsale-member_code').autocomplete({
//                            source: arr
//                        });
//                },
//                error:function(data){
//                            //alert('Your data has not been submitted..Please try again');
//                        }
//               
//            });
//        }
//    });
//    $('#tblmember-bank_code').on('change',function(){
//        $('#tblmember-ifsc').val('');
//        $('#tblmember-ifsc').prop('readonly', false);
//    });
//    $('#tblmember-branch_code').on('change',function(){
//            
//            var id = $(this).val();
//            $.ajax({
//                        type: 'post',
//                        url: '" . Yii::$app->request->baseUrl . "/organisation/tbl-branch/get-ifsc-code',
//                        data: 'id='+id,
//                        success: function(data) {
//                                var obj1 = $.parseJSON(data);
//                                $('#tblmember-ifsc').val(obj1.code);
//                                if(obj1.code!='')
//                                    $('#tblmember-ifsc').prop('readonly', true);
//                                else
//                                    $('#tblmember-ifsc').prop('readonly', false);
//                        },
//                        error:function(data){
//                                    //alert('Your data has not been submitted..Please try again');
//                                }
//            });
//    });
";
$this->registerJs($script, View::POS_END, 'union');
