<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

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
        // echo Html::activeHiddenInput($searchModel, 'union_code', ['value' => $searchModel->union_code]);
        // echo Html::activeHiddenInput($searchModel, 'plant_code', ['value' => $searchModel->plant_code]);
        // echo Html::activeHiddenInput($searchModel, 'mcc_plant_code', ['value' => $searchModel->mcc_plant_code]);
        // echo Html::activeHiddenInput($searchModel, 'bmc_code', ['value' => $searchModel->bmc_code]);
        // echo Html::activeHiddenInput($searchModel, 'type', ['value' => $searchModel->type]);
        $attribute = [
                ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key, $index) {
                    $code = $model['payment_transaction_code'].'###'.$model['bmc_code'].'###'.$model['type'];
                    return ['class' => 'checkbox', 'value' => $code];
                }],
                ['attribute' => 'union_code', 'filter' => FALSE],
                ['attribute' => 'plant_code', 'filter' => FALSE],
                ['attribute' => 'mcc_plant_code', 'filter' => FALSE],
                ['attribute' => 'bmc_code', 'filter' => FALSE],
                ['attribute' => 'name', 'filter' => FALSE],
                ['attribute' => 'type', 'filter' => FALSE],
                ['attribute' => 'payment_date', 'label' => Yii::t('app', 'Payment Date'), 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->payment_date);
                }, 'filter' => FALSE],
                ['attribute' => 'total_amount', 'filter' => FALSE],
                ['attribute' => 'total_deduction', 'filter' => FALSE],
                ['attribute' => 'final_amount', 'filter' => FALSE],
                // ['attribute' => 'disburse_amount', 'filter' => FALSE],
                // ['attribute' => 'qty', 'filter' => FALSE],
                // ['attribute' => 'avg_fat', 'filter' => FALSE],
                // ['attribute' => 'avg_snf', 'filter' => FALSE],
                // ['attribute' => 'kg_fat', 'filter' => FALSE],
                // ['attribute' => 'kg_snf', 'filter' => FALSE],
                // ['attribute' => 'avg_rate', 'filter' => FALSE],
                ['attribute' => 'bank_name', 'filter' => FALSE],
                ['attribute' => 'bank_code', 'filter' => FALSE],
                ['attribute' => 'branch_name', 'filter' => FALSE],
                ['attribute' => 'branch_code', 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form) {
                    echo Html::activeHiddenInput($model, '[' . $model->payment_transaction_code . ']payment_transaction_code', ['value' => $model->payment_transaction_code]);
                    return $form->field($model, '[' . $model->payment_transaction_code . ']branch_code')->textInput(['value' => $model->branch_code, 'class' => 'form-control number-validate', 'data-id' => $key])->label(FALSE);
                },
            ],
                ['attribute' => 'ifsc', 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, '[' . $model->payment_transaction_code . ']ifsc')->textInput(['value' => $model->ifsc, 'class' => 'form-control number-validate', 'data-id' => $key])->label(FALSE);
                },
            ],
                ['attribute' => 'bank_account_no', 'label' => Yii::t('app', 'bank_account_no'), 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model, $key, $index) use ($form) {
                    return $form->field($model, '[' . $model->payment_transaction_code . ']bank_account_no')->textInput(['value' => $model->bank_account_no, 'class' => 'form-control number-validate', 'data-id' => $key])->label(FALSE);
                },
            ],
            ['attribute' => 'status', 'filter' => FALSE],
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
                echo Html::button(Yii::t('app', 'Reinitiate'), ['class' => 'btn btn-primary submit mt10', 'id' => 'approve', 'value' => 'reinitiate', 'name' => 'reinitiate']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index-other', false, 'mt10'); ?> 
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
