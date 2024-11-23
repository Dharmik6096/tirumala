<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\helpers\Html;
?>
<div class="panel panel-default panel-main">
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'options' => ['id' => 'create-monthly-credit-limit-form'],
                    'validateOnBlur' => FALSE,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-2" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmonthlycreditlimit-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
            </div> 
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmonthlycreditlimit-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
            </div>      
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmonthlycreditlimit-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
            </div>
            <div class="col-sm-2">
                <?php
                if ($type == 'memberWiseCredit') {
                    echo Html::activeHiddenInput($model, 'customer_type');
                    echo Yii::$app->dropdown->bmc_society($model, $form, 'tblmonthlycreditlimit-bmc_code', 'dcs_code', $model->getAttributeLabel('dcs_code'));
                } else {
                    $where = json_encode(['is_product_sale' => 1]);
                    $notInArr = json_encode(['MEMBER']);
                    echo Html::hiddenInput('customer_type_depends', $where, ['id' => 'customer_type_depends']);
                    echo Html::hiddenInput('customer_type_depends_not_in', $notInArr, ['id' => 'customer_type_depends_not_in']);
                    echo Yii::$app->dropdown->customerType($model, $form, 'tblmonthlycreditlimit-union_code,customer_type_depends,customer_type_depends_not_in', 'customer_type', $model->getAttributeLabel('customer_type'), FALSE, FALSE);
                }
                ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'wef_date', '', true); ?>
            </div>
            <div class="clearfix"></div>
            <?php
            $lable = Yii::t('app', 'Code');
            if ($type === 'memberWiseCredit') {
                $lable = Yii::t('app', 'Member Code');
            }
            ?>
            <div class="col-sm-2 reset_field">
                <?= $form->field($model, 'ex_code')->textInput()->label($lable) ?>
            </div>
            <div class="col-sm-2 reset_field">
                <?php
                $lable = Yii::t('app', 'Name');
                if ($type === 'memberWiseCredit') {
                    $lable = Yii::t('app', 'Member name');
                }
                ?>
                <?= Html::activeHiddenInput($model, 'customer_code') ?>
                <?= $form->field($model, 'customer_name')->textInput(['readOnly' => true])->label($lable) ?>
            </div>
            <div class="col-sm-1 avlAmount reset_field">
                <?= $form->field($model, 'avl_amount')->textInput(['readOnly' => true])->label('Available Amount') ?>
            </div>
            <div class="col-sm-1 reset_field">
                <?= $form->field($model, 'final_amount')->textInput(['readOnly' => false]) ?>
            </div>

            <div class="clearfix"></div>
            <div class="col-sm-2 padding_top_20">
                <div class="form-group">
                    <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $(document).on('change', '#tblmonthlycreditlimit-bmc_code, #tblmonthlycreditlimit-dcs_code, #tblmonthlycreditlimit-customer_type, #tblmonthlycreditlimit-wef_date', function() {
        $('#tblmonthlycreditlimit-ex_code').val('').trigger('change');
        $('#tblmonthlycreditlimit-final_amount').val('').trigger('change');        
    });

    $(document).on('change', '#tblmonthlycreditlimit-ex_code', function() {
        setVendorCode();
    });

    function setVendorCode(){
        $('#tblmonthlycreditlimit-customer_code').val('');
        $('#tblmonthlycreditlimit-customer_name').val('');
        var code = $('#tblmonthlycreditlimit-ex_code').val();
        var type= $('#tblmonthlycreditlimit-customer_type').val(); 
        var union= $('#tblmonthlycreditlimit-union_code').val(); 
        var bmc= $('#tblmonthlycreditlimit-bmc_code').val(); 
        var date= $('#tblmonthlycreditlimit-wef_date').val(); 
        var plant= $('#tblmonthlycreditlimit-plant_code').val(); 
        var mcc= $('#tblmonthlycreditlimit-mcc_plant_code').val(); 
        var dcsCode = '';
        var formType = '" . $type . "';
        if(formType == 'memberWiseCredit') {
            dcsCode = $('#tblmonthlycreditlimit-dcs_code').val(); 
        }
        if(code != '' && code != null && code != undefined) {
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-customer']) . "',
                data: {'customer_code':code, 'dcsCode': dcsCode,'customer_type':type,'union_code':union,'bmc_code':bmc,'date':date,'plant':plant,'mcc':mcc},
                success: function(data) {                                        
                    var obj = $.parseJSON(data);
                    if (obj.status == 'success') {
                        $('#tblmonthlycreditlimit-customer_name').val(obj.data); 
                        $('#tblmonthlycreditlimit-customer_code').val(obj.code); 
                        $('#tblmonthlycreditlimit-customer_code').trigger('change');
                        setAvailableCredit();
                    }else{
                        bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>" . Yii::t('app', 'Please enter valid Code.') . "</span></div></div>', function(result){
                            setTimeout(function(){
                                $('#tblmonthlycreditlimit-ex_code').focus();
                            },100);
                        });           
                        $('#tblmonthlycreditlimit-customer_code').val('');                    
                        $('#tblmonthlycreditlimit-customer_name').val('');                    
                        $('#tblmonthlycreditlimit-customer_code').focus();
                    }
                },
                error:function(data){

                }
            });
        }
    }

    function setAvailableCredit() {
        var type = $('#tblmonthlycreditlimit-customer_type').val();
        var date = $('#tblmonthlycreditlimit-wef_date').val();
        var union = $('#tblmonthlycreditlimit-union_code').val();
        var bmc = $('#tblmonthlycreditlimit-bmc_code').val();
        var code = $('#tblmonthlycreditlimit-customer_code').val();
        if(setData(date) && setData(type) && setData(code)){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['set-available-credit']) . "',
                data: { 'date': date, 'type': type, 'code': code, 'union': union, 'bmc': bmc },
                success: function(data) {
                    var obj = $.parseJSON(data);
                    if (obj.status === 'success') {
                        $('#tblmonthlycreditlimit-avl_amount').val(obj.available_amount);
                    }
                },
                error: function(data) {
                }
            });
        }
    }

    function setData(field = ''){
        if(field != '' && field != null && field != undefined && field != 'Loading ...'){
            return true;
        }else {
            return false;
        }
    }  
";
$this->registerJs($script, View::POS_END, 'create-monthly-credit-limit-form');
?>