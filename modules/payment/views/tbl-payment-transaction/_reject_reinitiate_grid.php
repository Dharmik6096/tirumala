<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Reject Reinitiate');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'reject-reinitiate-form',
    ]);
    ?>
    <div class="">
        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key, $index) {
                    $code = $model['payment_transaction_code'] . '###' . $model['bmc_code'] . '###' . $model['type'] . '###' . date('Y-m-d', strtotime($model['from_date'])) . '###' . date('Y-m-d', strtotime($model['to_date']));
                    return ['class' => 'checkbox', 'value' => $code];
                }],
            ['attribute' => 'bmc_code',
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
                },
                'vAlign' => 'middle', 'filter' => false, 'enableSorting' => false],
            ['attribute' => 'bmc_code',
                'label' => Yii::t('app', 'BMC Name'),
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }, 'vAlign' => 'middle', 'filter' => false],
            ['attribute' => 'payment_cycle', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->from_date) . ' to ' . Yii::$app->controls->view_date($model->to_date);
                }, 'filter' => false, 'format' => 'raw'],
            ['attribute' => 'type'],
            ['attribute' => 'payment_date', 'label' => Yii::t('app', 'Payment Date'), 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->payment_date);
                }, 'filter' => FALSE],
            ['attribute' => 'final_amount', 'filter' => FALSE],
            ['attribute' => 'name', 'label' => Yii::t('app', 'name'), 'value' => function($model, $key, $index) use ($form) {
                    return $form->field($model, '[' . $model->payment_transaction_code . ']name')->textInput(['value' => $model->name, 'class' => 'form-control', 'data-id' => $key])->label(FALSE);
                }, 'format' => 'raw', 'filter' => FALSE],
            ['attribute' => 'code', 'filter' => FALSE],
            ['attribute' => 'ref_code', 'value' => function ($model) {
                return Yii::$app->general->getField($model, $model->type, 'ref_code');
            }],
            ['attribute' => 'bank_account_no', 'label' => Yii::t('app', 'Bank Account No'), 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, '[' . $model->payment_transaction_code . ']bank_account_no')->textInput(['value' => $model->bank_account_no, 'class' => 'form-control number-validate', 'data-id' => $key])->label(FALSE);
                },
            ],
            ['attribute' => 'bank_name', 'filter' => FALSE],
            ['attribute' => 'ifsc', 'filter' => FALSE],
            ['attribute' => 'response_msg', 'label' => Yii::t('app', 'Failed Reson'), 'filter' => FALSE],
        ];

        $grid_option = [
            'id' => 'reject-reinitiate-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['reject-reinitiate'], false);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Reinitiate'), ['class' => 'btn btn-primary submit mt10 btn-login', 'id' => 'approve', 'value' => 'reinitiate', 'name' => 'reinitiate']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index-other', false, 'mt10 btn-login'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
        $("#reject-reinitiate-form").submit();
    });
';
$this->registerJs($script, View::POS_END, 'reject-reinitiate-form');
