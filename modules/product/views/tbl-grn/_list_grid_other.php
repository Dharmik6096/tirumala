<?php

use app\modules\usermanagement\components\GhostHtml;
use kartik\grid\GridView;
use app\components\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$batchNoWiseInventory = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'batch_no_wise_inventory', 'PORTAL');
$visible = $batchNoWiseInventory == 1 ? TRUE : FALSE;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'product-wise-detail',
            'action' => 'create-other',
        ]);
?>
<div class="col-sm-12 padding-left-0 padding-right-0 ">
    <h5 class="panel-heading"><?= Yii::t('app', 'Product Details') ?></h5>
    <?php
    echo Html::activeHiddenInput($model, 'union_code', ['id' => 'set_union_code']);
    echo Html::activeHiddenInput($model, 'plant_code', ['id' => 'set_plant_code']);
    echo Html::activeHiddenInput($model, 'mcc_plant_code', ['id' => 'set_mcc_plant_code']);
    echo Html::activeHiddenInput($model, 'bmc_code', ['id' => 'set_bmc_code']);
    echo Html::activeHiddenInput($model, 'grn_no', ['id' => 'set_grn_no']);
    echo Html::activeHiddenInput($model, 'grn_date', ['id' => 'set_grn_date']);
    echo Html::activeHiddenInput($model, 'invoice_date', ['id' => 'set_invoice_date']);
    echo Html::activeHiddenInput($model, 'invoice_no', ['id' => 'set_invoice_no']);
    echo Html::activeHiddenInput($model, 'ref_no', ['id' => 'set_ref_no']);
    echo Html::activeHiddenInput($model, 'remarks', ['id' => 'set_remarks']);
    echo Html::activeHiddenInput($model, 'payment_mode', ['id' => 'set_payment_mode']);
    echo Html::activeHiddenInput($model, 'no_of_installment', ['id' => 'set_no_of_installment']);
    echo Html::activeHiddenInput($model, 'deduction_start_date', ['id' => 'set_deduction_start_date']);

    $attribute = [
        ['attribute' => 'product_code', 'value' => function ($model, $key, $index) {
                echo Html::activeHiddenInput($model, '[' . $index . ']plant_dispatch_txn_code', ['value' => $model->plant_dispatch_txn_code]);
                echo Html::activeHiddenInput($model, '[' . $index . ']product_code', ['value' => $model->product_code]);
                echo Html::activeHiddenInput($model, '[' . $index . ']unit_code', ['value' => $model->unit_code]);
                echo Html::activeHiddenInput($model, '[' . $index . ']sap_batch_no', ['value' => $model->sap_batch_no]);
                return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
            }, 'filter' => FALSE],
        ['attribute' => 'sap_batch_no', 'visible' => $visible, 'filter' => false],
        ['attribute' => 'unit_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unitCode, 'unit_name');
            }, 'visible' => TRUE, 'filter' => false],
        ['attribute' => 'rate', 'filter' => false],
        ['attribute' => 'dispatch_qty',
            'format' => 'raw',
            'filter' => FALSE,
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'dispatch_qty_change\'>' . $form->field($model, '[' . $index . ']dispatch_qty')->textInput(['value' => $model->grn_missing_qty, 'class' => 'form-control number-validate-js', 'readonly' => TRUE])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'received_qty',
            'format' => 'raw',
            'filter' => FALSE,
            'value' => function ($model, $key, $index) use ($form) {
                return Html::activeHiddenInput($model, '[' . $index . ']rate', ['value' => $model->rate, 'class' => 'rateField']) . '<span class=\'received_qty_change\'>' . $form->field($model, '[' . $index . ']received_qty')->textInput(['value' => $model->grn_missing_qty, 'class' => 'form-control number-validate-js', 'readonly' => TRUE])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'rejected_qty',
            'format' => 'raw',
            'filter' => FALSE,
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'rejected_qty_change\'>' . $form->field($model, '[' . $index . ']rejected_qty')->textInput(['value' => $model->rejected_qty, 'class' => 'form-control number-validate-js number-validate',])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'missing_qty',
            'format' => 'raw',
            'label' => 'Pending Qty',
            'filter' => FALSE,
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'missing_qty_change\'>' . $form->field($model, '[' . $index . ']missing_qty')->textInput(['value' => $model->missing_qty, 'class' => 'form-control number-validate-js number-validate'])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'amount',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return $form->field($model, '[' . $index . ']amount')->textInput(['class' => 'form-control amountField', 'readonly' => TRUE])->label(FALSE);
            },
        ],
        ['attribute' => 'rejection_remarks',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return $form->field($model, '[' . $index . ']rejection_remarks')->textInput(['class' => 'form-control'])->label(FALSE);
            },
        ],
        ['attribute' => 'missing_remarks',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return $form->field($model, '[' . $index . ']missing_remarks')->textInput(['class' => 'form-control'])->label(FALSE);
            },
        ],
        ['attribute' => 'manuf_date',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span>' . Yii::$app->controls->date($model, $form, '[' . $index . ']manuf_date', '', date('Y-m-d'), false, false, false) . '</span>';
            },
        ],
    ];


    $grid_option = [
        'id' => 'product-wise-detail-test',
        'attributes' => $attribute,
        'active_column' => FALSE,
//        'default_sorting' => FALSE
    ];
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
<!--<div class="panel-footer" >
<?php
if (!empty($dataProvider->getModels())) {
    echo Html::button(Yii::t('app', 'Save'), ['class' => 'btn-login btn btn-primary', 'id' => 'update']);
}
?>
<?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
</div>-->

<div class="col-sm-12 mt25 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php
        if (!empty($dataProvider->getModels())) {
            AjaxSubmitButton::begin([
                'label' => Yii::t('app', 'create'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => Url::to(['create-other']),
                    'data' => new JsExpression("$('#product-wise-detail').serializeArray()"),
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
                                                                }else{
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                    });
                                                                    $(".error-summary").show();
                                                                }
                                                 }'),
                ],
                'options' => ['class' => 'btn-login btn btn-default btn-raised',
                    'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
        }
        ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
