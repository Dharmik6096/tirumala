<?php

use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
?>
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
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('committee_type_code', $model, $form, '', $model->getAttributeLabel('committee_type_code'), $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblcommitteemembers-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), false, '', $readonly); ?>  
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblcommitteemembers-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), false, '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblcommitteemembers-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code'), false, '', '', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblcommitteemembers-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'), false, '', $readonly); ?>         
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'tblcommitteemembers-dcs_code', '', Yii::t('app', 'Member'), 'member_code', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'member_name')->textInput(['readOnly' => true]) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->local($model, $form); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'election_date', '', FALSE); ?> 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'tenure_from_date', '', FALSE); ?> 
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'tenure_to_date', '', FALSE); ?> 
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
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
$('#tblcommitteemembers-member_code').change(function(){
        $('#tblcommitteemembers-member_name').val('');
        var code = $(this).val();
        
        if(code != '' && code != null && code != undefined) {
            $.ajax({
                type: 'post',
                url:'" . Url::to(['get-member']) . "',
                data: {'member_code':code},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        $('#tblcommitteemembers-member_name').val(obj.data); 
                    }else{                  
                        $('#tblcommitteemembers-member_name').val('');
                    }
                },
                error:function(data){

                }
            });
        }
});
";
$this->registerJs($script, View::POS_END, 'create-product-sale-form');
?>