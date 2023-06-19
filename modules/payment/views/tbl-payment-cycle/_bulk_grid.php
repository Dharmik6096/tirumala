<?php

use yii\bootstrap5\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Bulk Data LOCK/UNLOCK');
$button = !empty($searchModel->data_status) && $searchModel->data_status == 1 ? 'LOCK' : 'UNLOCK';
$data_status = !empty($searchModel->data_status) && $searchModel->data_status == 1 ? 0 : 1;
$check_for = !empty($searchModel->check_for) ? $searchModel->check_for : '';
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'bulk-data-lock-unlock',
    ]);
    ?>
    <div class="">

        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model) use($data_status, $check_for) {
                    return ['class' => 'checkbox', 'value' => $model['payment_cycle_applicabilty_code'] . '###' . $data_status . '###' . $check_for];
                }],
            ['attribute' => 'applicable_type', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
                }, 'filter' => false],
            ['attribute' => 'applicable_code', 'label' => Yii::t('app', 'Applicable Code'), 'filter' => false],
            ['attribute' => 'applicable_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_code_ex');
                }, 'filter' => false],
            ['attribute' => 'applicable_code', 'label' => Yii::t('app', 'Ref. Code'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
                }, 'filter' => false],
            ['attribute' => 'applicable_code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }, 'filter' => false],
            ['attribute' => 'payment_cycle_code', 'filter' => false],
            [
                'attribute' => 'from_date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
                    return Yii::$app->controls->view_date($model->from_date);
                }, 'filter' => FALSE],
            [
                'attribute' => 'to_date',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy',
                        'autoclose' => true]
                ],
                'value' => function($model) {
                    return Yii::$app->controls->view_date($model->to_date);
                }, 'filter' => FALSE],
            ['attribute' => 'data_lock_bmc', 'value' => function($model) {
                    return $model->data_lock_bmc == 1 ? 'Unlock' : 'Lock';
                }, 'filter' => false],
            ['attribute' => 'sync_lock_bmc', 'value' => function($model) {
                    return $model->sync_lock_bmc == 1 ? 'Unlock' : 'Lock';
                }, 'filter' => false],
            ['attribute' => 'billing_lock_bmc', 'value' => function($model) {
                    return $model->billing_lock_bmc == 1 ? 'Unlock' : 'Lock';
                }, 'filter' => false],
            ['attribute' => 'data_lock_member', 'value' => function($model) {
                    return $model->data_lock_member == 1 ? 'Unlock' : 'Lock';
                }, 'filter' => false],
            ['attribute' => 'sync_lock_member', 'value' => function($model) {
                    return $model->sync_lock_member == 1 ? 'Unlock' : 'Lock';
                }, 'filter' => false],
            ['attribute' => 'billing_lock_member', 'value' => function($model) {
                    return $model->billing_lock_member == 1 ? 'Unlock' : 'Lock';
                }, 'filter' => false],
        ];

        $grid_option = [
            'id' => 'bulk-data-lock-unlock',
            'attributes' => $attribute,
            'active_column' => false,
//            'showPageSummary' => false,
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', $button), ['class' => 'btn btn-primary', 'id' => 'delete']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'bulk-data-lock-unlock'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = '
    $(".kv-panel-before").hide();
    $("#delete").click(function() {
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Applicability.</span></div></div>");
                return false;
            } else {
            $("#bulk-data-lock-unlock").submit();
            }
         });
      ';
$this->registerJs($script, View::POS_END, 'data-lock-unlock');
