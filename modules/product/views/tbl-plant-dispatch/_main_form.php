<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$readonly = $type == 'create' ? FALSE : TRUE;
$disable = $readonly ? 'disabled' : '';
$list = array('0' => 'No', '1' => 'Yes');
$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL') == 1 ? TRUE : FALSE;
$sapBatchDisable = $batchNoWiseInventory == 1 ? '' : 'disp_none';
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'grn-form'],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row table_form theme-box theme_border_right theme_border_left theme_border_bottom">
    <div class="single_entry_area col-sm-12 padding_10_0 DisableAferAdd">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Plant Dispatch') ?></h4>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), $readonly); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->controls->date($model, $form, 'dispatch_date', '', TRUE, date('Y-m-d'), TRUE, true); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->depend_dropdown('vendor', $model, $form, 'tblplantdispatch-union_code', 'form-group', $model->getAttributeLabel('vendor_master_code'), 'vendor_master_code'); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblplantdispatch-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblplantdispatch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblplantdispatch-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?= $form->field($model, 'document_no')->textInput() ?>
        </div>
        <div class="col-sm-2 create_fields">
            <?php
            $minDate = date('Y-m-d', strtotime("-4 days"));
            ?>
            <?= Yii::$app->controls->date($model, $form, 'document_date', '', date('Y-m-d'), $minDate, false, true); ?>
        </div>
        <div class="col-sm-4 create_fields">
            <?= $form->field($model, 'remarks')->textInput() ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"></div>
    <div class="col-md-10 padding_10_0 theme-box view-subtitle QltyParamDiv">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
            <h4 class="theme-box-heading">Product Details</h4>
        </div>
        <div class="col-sm-3 reset_field">
            <?= Html::hiddenInput('x_col3', '2', ['id' => 'x_col3']); ?>
            <?php Yii::$app->dropdown->depend_dropdown('product', $txModel, $form, 'tblplantdispatch-union_code,x_col3', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Product', 'product_code', FALSE); ?>
        </div>
        <?php
        if ($batchNoWiseInventory == 1) {
            ?>
            <div class="col-sm-2 sap_batch_no">
                <?= $form->field($txModel, 'sap_batch_no')->textInput() ?>
            </div>
        <?php } ?>
        <div class=" col-sm-1 reset_field unit disabledDiv">
            <?= Yii::$app->dropdown->dropdown('unit_code', $txModel, $form, 'form-group col-sm-2', $txModel->getAttributeLabel('unit_code'), FALSE, 'unit_code'); ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'rate')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'qty')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($txModel, 'amount')->textInput(['readonly' => TRUE]) ?>
        </div>
        <div class="col-sm-1 reset_field">
            <?= $form->field($txModel, 'lr_no')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'product_mrp')->textInput() ?>
        </div>
        <div class="col-sm-2 reset_field number-validate">
            <?= $form->field($txModel, 'distributor_landing_rate')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'sachiv_price')->textInput() ?>
        </div>
        <div class="col-sm-1 reset_field number-validate">
            <?= $form->field($txModel, 'member_price')->textInput() ?>
        </div>
        <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <div class="form-group">
                <?=
                Html::a(Yii::t('app', 'Add'), 'javascript:void(0)', ['class' => 'btn btn-primary add-asset-record disabled no_pointer'])
                ?>
                <?php
                //                Html::a(Yii::t('app', 'Add Sr. No.'), 'javascript:void(0)', ['class' => 'btn btn-primary add-serial-record disabled no_pointer'])
                ?>
            </div>

        </div>
    </div>
</div>
<div class="col-sm-12">
    <table class="table table-bordered table-striped table-main table-language br_grey bl_grey asset_transaction_table">
        <thead>
            <tr>
                <th><?= Yii::t('app', 'Product Code') ?></th>
                <th><?= $txModel->getAttributeLabel('product_code') ?></th>
                <th><?= $txModel->getAttributeLabel('unit_code') ?></th>
                <?php
                if ($batchNoWiseInventory) {
                    echo '<th>' . $txModel->getAttributeLabel('sap_batch_no') . '</th>';
                }
                ?>
                <th><?= $txModel->getAttributeLabel('rate') ?></th>
                <th><?= $txModel->getAttributeLabel('qty') ?></th>
                <th><?= $txModel->getAttributeLabel('amount') ?></th>
                <th><?= $txModel->getAttributeLabel('lr_no') ?></th>
                <th><?= $txModel->getAttributeLabel('product_mrp') ?></th>
                <th><?= $txModel->getAttributeLabel('distributor_landing_rate') ?></th>
                <th><?= $txModel->getAttributeLabel('sachiv_price') ?></th>
                <th><?= $txModel->getAttributeLabel('member_price') ?></th>
                <th><?= Yii::t('app', 'Action') ?></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<div class="col-sm-12 mt25 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php
        AjaxSubmitButton::begin([
            'label' => Yii::t('app', 'Save'),
            'ajaxOptions' => [
                'type' => 'POST',
                'url' => Url::to(['create']),
                'beforeSend' => new JsExpression("function(data){
                                            $('#loadercontent').show();
                                            $('#pageloader').show();
                                        }"),
                'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $(\'#loadercontent\').hide();
                                                                $(\'#pageloader\').hide();
                                                                if (data.status == "success"){ 
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");         
                                                                     $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                     bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                }
                                                 }'),
            ],
            'options' => [
                'class' => 'btn btn-default btn-save-txn disabled no_pointer',
                'type' => 'submit'
            ],
        ]);
        AjaxSubmitButton::end();
        ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>