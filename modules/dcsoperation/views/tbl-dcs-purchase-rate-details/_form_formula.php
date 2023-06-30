<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
?>
<div class="modal modal-default fade" id="calculate-rate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Select Formula'); ?></h4>
            </div>

            <?php
            $form = ActiveForm::begin(['options' => [
                            'validateOnBlur' => true,
                            'class' => 'popup-form',
                            'id' => 'formula-select',
                            'enableAjaxValidation' => false,
                        ], 'fieldConfig' => [
            ]]);
            ?>
            <div class="modal-body">         
                <div class="row">
                    <div class="col-sm-12 mb15">
                        <div class="radio-list">
                            
                        </div>
                        <?php
                        //$key = key($formulaArray);
                        //echo Html::radioList('formula', '', $formulaArray, ['class' => 'radio radio-list', 'itemOptions' => ['class' => ''],]);

                        ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php foreach ($rateNames as $name){ ?>
                        <div id="<?= $name ?>" class="col-sm-6">
                            <?= Html::label($name.' KG'); ?>      
                            <?= Html::textInput($name, '', ['class' => 'form-control number-validate popup-values', 'id' => $name.'-value']); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Ok'), ['class' => 'btn btn-primary', 'id' => 'apply-calculation']); ?>

                <button type="button" class="btn btn-danger close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
    <?php
$id = $_GET['id'];
$script = "
   
   var records;
    var jsonEncoded = '".$jsonEncoded."';
    if(jsonEncoded==''){    
        records = localStorage.getItem('purchaseRate');
    }else{
        records = jsonEncoded;
    }
    
    $('#purchase_rate').val(records);
    
    $('#apply-calculation').on('click',function(e){
               var ln = $('input[name=\'formula\']:checked').length;
               if(ln==0){
                     bootbox.alert('Please Select Formula.');
                     return false;
               }else{
                    var check=0;
                    $( '.popup-values' ).each(function( index, element ) {
                       if(this.value==''){
                        check=1;
                       }
                    });
                    if(check==1){
                        bootbox.alert('<div class=\'bg-info\'><i class=\'fa fa-question\'></i></div>Please add values.');
                        return false;
                    }else{
                        var value = $('input[name=\'formula\']:checked').val();
                        var fat = $('#FAT-value').val();
                        var snf = $('#SNF-value').val();
                        $('#formula-label').html(value);
                        $('.formula-value').val(value);
                        $('#fat-kg').html(fat);
                        $('#snf-kg').html(snf);
                        $('.kg-rate-SNF').val(snf);
                        $('.kg-rate-FAT').val(fat);
                        $('#formula-info').css('display','block');
                        $('#calculate-rate').modal('toggle');
                    }
               }
    });
    
    $('#apply-formula').on('click',function(e){
                $('#calculate-rate').modal('toggle');
    });
    
    $('.close-import').on('click',function(e){
               $('#apply-formula').prop('checked', false);
    });

    $('#tbldcspurchaseratebased-0-milk_type_code').on('change',function(e){
               
            var data = $('#purchase_rate').val();
            var obj = $.parseJSON(data);
            
            var milkType = this.value;
            var rateType = obj.rate_type;
            var unionCode= obj.union_code;
            $('#formula-label').html('');
            $('.formula-value').val('');
            $('#fat-kg').html('');
            $('#snf-kg').html('');
            $('#formula-info').css('display','none');
            $.ajax({
                type: 'post',
                url: '" . Url::to(['/dcsoperation/tbl-dcs-purchase-rate-details/get-auto-formulas']) . "',     
                data: 'milkType='+milkType+'&rateType='+rateType+'&union_code='+unionCode,
                success: function(data) {
                        var obj1 = $.parseJSON(data);
                        $('.number-validate').val('');
                        $( '.radio-list' ).empty();
                        $( '.radio-list' ).append(obj1.values);
                },
                error:function(data){
                            //alert('Your data has not been submitted..Please try again');
                        }
            });
    });     
         

    //$('input[name=\'formula\']').on('change',function(e){
    $('#formula-select').on('change','input[name=\'formula\']',function(e){
                 var value = $('input[name=\'formula\']:checked').val();
                 $('.formula-value').val(value);
                 $('#formula-label').html(value);
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>