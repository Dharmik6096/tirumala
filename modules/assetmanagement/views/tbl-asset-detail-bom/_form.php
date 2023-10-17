<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$disabled = 'disabled';
$model->asset_detail_code = Yii::$app->request->get('id');
$model->is_active = $model->assetCode['is_active'];
$form = ActiveForm::begin([
            'options' => ['id' => 'asset-master-from'],
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Html::activeHiddenInput($model, 'asset_detail_bom_code'); ?>
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Html::activeHiddenInput($model, 'asset_code'); ?>
        <?php Yii::$app->dropdown->bom_code($model, $form, 'tblassetdetailbom-asset_code', 'spare_code', $model->getAttributeLabel('spare_code')); ?>
    </div>
    <div class="col-sm-2 hide-qty-no">
        <?= $form->field($model, 'qty')->textInput(['value' => 1]) ?>
    </div>
    <div class="col-sm-2 hide-serial-no">
        <?= $form->field($model, 'serial_number')->textInput() ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_active'); ?>
    </div>
    <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
$(document).ready(function(){
   $('.hide-serial-no').hide(); 
   $('.hide-qty-no').hide(); 
      
    });
    $('#tblassetdetailbom-spare_code').on('change',function(){
       hideSerialno();   
   });
   $('.edit-record').on('click',function(event){       
        var id= $(this).attr('data-code');
        editAssetDetailBom(id);
    });
    function hideSerialno(){
        var spare_code = $('#tblassetdetailbom-spare_code').val(); 
        var asset_code = $('#tblassetdetailbom-asset_code').val(); 
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/assetmanagement/tbl-asset-detail-bom/get-asset-is-serial']) . "',
                data: {'spare_code' : spare_code, 'asset_code' : asset_code},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success'){
                    $('#is_serial_number').val(obj1.is_serial_number);
                    if(obj1.is_serial_number == 0){
                      $('.hide-serial-no').hide(); 
                      $('.hide-qty-no').show(); 
                      $('#tblassetdetailbom-serial_number').val('');
                    }
                    else{
                      $('.hide-serial-no').show();
                      $('.hide-qty-no').hide(); 
                    }
                }
            },
        });
    }
    
    function editAssetDetailBom(asset_detail_bom_code){
            if(asset_detail_bom_code != ''){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['update-bom']) . "',
                    data: {'asset_detail_bom_code' : asset_detail_bom_code},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                    console.log(data.modelData);
                        $.each(data.modelData, function(index, value) {
                            if(index == 'is_active' && value == 0){
                                $('#tblassetdetailbom-'+index).removeAttr('checked');
                            } else {
                                $('#tblassetdetailbom-'+index).val(value);
                                $('#tblassetdetailbom-'+index).prop('checked',true);
                            }                            
                        });
                        $('#tblassetdetailbom-spare_code option:selected').trigger('change');
                         $('#loadercontent').hide();
                         $('#pageloader').hide();
                         $(window).scrollTop(0);

                    },
                });
            }
    };
";
$this->registerJs($script, View::POS_END, 'serial-no-hide');
?>




