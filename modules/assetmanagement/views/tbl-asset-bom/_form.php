<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$disabled = 'disabled';
$id = Yii::$app->request->get('id');
$url = Url::to(['create', 'id' => $id]);

$model->asset_code = $id;
$model->is_serial_number = $model->assetCode['is_serial_number'];
$model->is_active = $model->assetCode['is_active'];


$form = ActiveForm::begin([
            'action' => $url,
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Html::activeHiddenInput($model, 'asset_bom_code'); ?>
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('asset_code', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('select_asset_name'), $disabled); ?>
    </div>
    <div class="col-sm-2">
        <?php $model->asset_code = $model->spare_code; ?>
        <?= Yii::$app->dropdown->dropdown('spare_code', $model, $form, 'form-group col-sm-2', $model->getAttributeLabel('select_spare_name')); ?>
    </div>

    <div class="col-sm-2 hide-qty-no">
        <?= $form->field($model, 'qty')->textInput(['value' => 1]) ?>
    </div>
    <div class="col-sm-2 mt15 disp_none">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_serial_number'); ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_active'); ?>
    </div>
</div>
<div class="row">
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
    $('#tblassetbom-spare_code').on('change',function(){
       hideSerialno();
   });
   $('.edit-record').on('click',function(event){       
        var id= $(this).attr('data-code');
        editAssetBom(id);
    });

   function hideSerialno(){
        var spare_code = $('#tblassetbom-spare_code').val(); 
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/assetmanagement/tbl-asset-master/get-asset-is-serial']) . "',
                data: {'spare_code' : spare_code},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success'){
                    $('#is_serial_number').val(obj1.is_serial_number);
                    if(obj1.is_serial_number == 1){
                       $('#tblassetbom-is_serial_number').prop('checked', true);
                    }
                    else{
                     $('#tblassetbom-is_serial_number').prop('checked', false);
                    }
                }
            },
        });
    }
    function editAssetBom(asset_bom_code){
            if(asset_bom_code != ''){         
            $.ajax({
                    type: 'post',
                    url: '" . Url::to(['update-bom']) . "',
                    data: {'asset_bom_code' : asset_bom_code},
                    beforeSend:function(data) {
                    $('#loadercontent').show();
                    $('#pageloader').show();
                    },
                    success: function(data) {
                    console.log(data.modelData);
                        $.each(data.modelData, function(index, value) {
                            if(index == 'is_active' && value == 0){
                                $('#tblassetbom-'+index).removeAttr('checked');
                            } else {
                                $('#tblassetbom-'+index).val(value);
                                $('#tblassetbom-'+index).prop('checked',true);
                            } 
                              
                        });
                        $('#tblassetbom-spare_code option:selected').trigger('change');
                         $('#loadercontent').hide();
                         $('#pageloader').hide();
                         $(window).scrollTop(0);

                    },
                });
            }
    };
";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>

