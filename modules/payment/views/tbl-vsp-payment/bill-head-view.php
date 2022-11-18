<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\Html;

$society_name = !empty($dataProvider->getModels()) ?
        Yii::$app->general->getCustomer($searchModel->vspPaymentCode, $searchModel->vspPaymentCode->customer_type) . ' (' .
        Yii::$app->general->getCustomer($searchModel->vspPaymentCode, $searchModel->vspPaymentCode->customer_type, TRUE) . '-' .
        Yii::$app->general->getforeignkey($searchModel->vspPaymentCode->customerType, 'customer_desc') . ')' : '';
$update_count = 0;
?>
<div class="modal modal-default fade" id="BillHeadModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Bill Head Detail of ') . $society_name ?></h4>
            </div>
            <div class="popup-header bg_white">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'class' => 'form-group popup-form',
                                'id' => 'vendor-head-skip-form',
                            ],
                            'action' => Url::to(['/payment/tbl-vsp-payment/bill-head', 'code' => $model->vsp_payment_code])
                ]);
                ?>

                <?php
                $attribute = [
                        ['attribute' => 'bill_head_code', 'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
                        }
                    ],
                        ['attribute' => 'bill_head_type',
                        'value' => function($model) {
                            return isset($model->billHeadCode->bill_head_type) ? Yii::$app->dropdown->getRecords('bill_head_type')['data'][$model->billHeadCode->bill_head_type] : 'N/A';
                        },],
                        ['attribute' => 'amount'],
                        ['attribute' => 'is_hold',
                        'value' => function($model) {
                            return $model->is_hold == 1 ? 'Yes' : 'No';
                        },],
                        ['attribute' => 'current_cycle',
                        'value' => function($model) {
                            return $model->is_skippable == 1 ? $model->payment_cycle_type : 'N/A';
                        },],
                        ['attribute' => 'payment_cycle_type',
                        'format' => 'raw',
                        'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                        'value' => function ($model, $key, $index) use ($form, &$update_count) {
                            if ($model->is_skippable == 1) {
                                $update_count++;
                                if ($model->payment_cycle_type == 'first') {
                                    $items = ['first' => 'first', 'second' => 'second', 'third' => 'third', 'first-next' => 'first-NextMonth'];
                                } else if ($model->payment_cycle_type == 'second') {
                                    $items = ['second' => 'second', 'third' => 'third', 'first-next' => 'first-NextMonth'];
                                } else if ($model->payment_cycle_type == 'third') {
                                    $items = ['third' => 'third', 'first-next' => 'first-NextMonth'];
                                } else {
                                    $items = ['consecutive' => 'consecutive', 'consecutive-next' => 'consecutive-Next', 'first-next' => 'first-NextMonth'];
                                }
                                return Html::activeHiddenInput($model, '[' . $index . ']tbl_vsp_payment_transaction_code', ['value' => $model->tbl_vsp_payment_transaction_code]) . Html::activeHiddenInput($model, '[' . $index . ']current_cycle', ['value' => $model->payment_cycle_type]) . $form->field($model, '[' . $index . ']payment_cycle_type')->dropDownList($items, ['class' => ''])->label(FALSE);
                            }
                        },],
                ];
                $grid_option = [
                    'id' => 'bill-head-detail-list',
                    'attributes' => $attribute,
                    'active_column' => FALSE,
                ];
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
                ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class='row pad-10'>
                <div class="col-md-12">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'vendor-installment-form',
                                ],
                                'action' => Url::to(['/payment/tbl-vsp-payment/bill-head', 'code' => $model->vsp_payment_code])
                    ]);
                    ?>
                    <div class="row">
                        <div class="grid-search no-effect" >

                            <?php
                            $attribute = [
                                    ['class' => 'kartik\grid\CheckboxColumn',
                                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                                    'checkboxOptions' => function($model) {
                                        return ['class' => 'checkbox-installment', 'checked' => !empty($model->installment_date) ? TRUE : FALSE, 'value' => $model->product_sale_installment_code];
                                    }],
                                    ['label' => 'Sale Date', 'attribute' => 'invoice_date',
                                    'value' => function($model) {
                                        return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->saleCode, 'invoice_date'));
                                    }, 'filter' => false],
                                    ['attribute' => 'main_amount', 'filter' => FALSE],
                                    ['attribute' => 'installment_amount', 'filter' => FALSE],
                            ];

                            $grid_options = [
                                'id' => 'vendor-installation-list',
                                'attributes' => $attribute,
                                'active_column' => false,
                                'showPageSummary' => false,
                            ];

                            Yii::$app->grid->bind($idataProvider, $instalSearch, $grid_options, ['#'], false);
                            ?>
                        </div>
                    </div>
                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12 top-bottom-15 padding-50">
                            <?php
                            if (!empty($idataProvider->getModels()) || $update_count > 0) {
                                echo Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-primary add-installment', 'id' => 'add-installment']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>
<?php
$script = "$('.kv-panel-before').hide();$('.filters').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>