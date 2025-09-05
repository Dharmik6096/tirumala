<?php

														  
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Product Sale Delete');
?>
<div class=" no-effect">
<?php
$form = ActiveForm::begin([
            'id' => 'product-sale-bulk-delete',
        ]);
?>
    <div class="">
    <?php
    echo Html::activeHiddenInput($searchModel, 'union_code', ['value' => $searchModel->union_code]);
    echo Html::activeHiddenInput($searchModel, 'plant_code', ['value' => $searchModel->plant_code]);
    echo Html::activeHiddenInput($searchModel, 'mcc_plant_code', ['value' => $searchModel->mcc_plant_code]);
    echo Html::activeHiddenInput($searchModel, 'bmc_code', ['value' => $searchModel->bmc_code]);
    echo Html::activeHiddenInput($searchModel, 'from_date', ['value' => $searchModel->from_date]);
    echo Html::activeHiddenInput($searchModel, 'to_date', ['value' => $searchModel->to_date]);
    ?>
        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function ($model, $key, $index) {
                    echo Html::activeHiddenInput($model, 'operation', ['value' => $model->operation, 'class' => 'set_operation']);
                    return ['class' => 'checkbox', 'value' => $model['product_sale_code']];
                }],
            ['attribute' => 'union_code', 'value' => 'unionCode.union_name', 'filter' => false],
            ['attribute' => 'bmc_code', 'value' => function ($searchModel) {
                    return Yii::$app->general->getforeignkey($searchModel->bmcCode, 'bmc_name');
                }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'customer_type', 'value' => function ($searchModel) {
                    return isset($searchModel->customer_type) ? (strtolower($searchModel->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($searchModel->customerType, 'customer_desc') ) : '';
                }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code')],
            ['label' => Yii::t('app', 'Code Ex.'), 'value' => function ($searchModel) {
                    return isset($searchModel->customer_type) ? Yii::$app->general->getCustomer($searchModel, $searchModel->customer_type, true) : '';
                }, 'vAlign' => 'middle'],
            ['label' => Yii::t('app', 'Code'), 'value' => function ($searchModel) {
                    return isset($searchModel->customer_type) ? Yii::$app->general->getCustomer($searchModel, $searchModel->customer_type, FALSE, FALSE, true) : '';
                }, 'vAlign' => 'middle'],
            ['attribute' => 'customer_name', 'label' => Yii::t('app', 'name'), 'value' => function ($searchModel) {
                    return isset($searchModel->customer_type) ? Yii::$app->general->getCustomer($searchModel, $searchModel->customer_type) : '';
                }, 'vAlign' => 'middle'],
            [
                'attribute' => 'invoice_date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function ($searchModel) {
                    return Yii::$app->controls->view_date($searchModel->invoice_date);
                }],
            ['attribute' => 'amount', 'format' => Yii::$app->general->CurrencyFormat(), 'filter' => false],
            ['attribute' => 'other_amount', 'format' => Yii::$app->general->CurrencyFormat(), 'filter' => false],
            ['attribute' => 'discount', 'format' => Yii::$app->general->CurrencyFormat(), 'filter' => false],
            ['attribute' => 'amount_due', 'format' => Yii::$app->general->CurrencyFormat(), 'filter' => false],
            ['attribute' => 'paid_amount', 'format' => Yii::$app->general->CurrencyFormat(), 'filter' => false],
            ['attribute' => 'error_desc', 'label' => Yii::t('app', 'Error Description'),
                'value' => function ($searchModel) {
                    return $searchModel->error_desc;
                }, 'visible' => in_array($type, ['memberBulkDeleteApproval', 'vendorBulkDeleteApproval']),
            ],
        ];

        $grid_option = [
            'id' => 'product-sale-bulk-delete-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['product-sale-bulk-delete']);
        ?>

        <div class="panel-footer">
        <?php
        if (!empty($dataProvider->getModels())) {
            if (in_array($type, ['memberBulkDelete', 'vendorBulkDelete'])) {
                echo Html::button(Yii::t('app', 'Delete'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'delete', 'value' => 'delete', 'name' => 'delete']);
            } else if (in_array($type, ['memberBulkDeleteApproval', 'vendorBulkDeleteApproval'])) {
                echo Html::button(Yii::t('app', 'Approve'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']);
                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
            }
        }
        ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index', '', 'btn-login'); ?>
        </div>
            <?php ActiveForm::end(); ?>
    </div>
</div>



<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
        var id= $(this).attr("value");
        $(".set_operation").val(id);
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
        if(len == 0){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
            return false;
        } else {
            $("#product-sale-bulk-delete").submit();
        }
    });
';
$this->registerJs($script, View::POS_END, 'product-sale-bulk-delete');

