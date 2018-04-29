<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblDcsPaymentCycle */

$this->title = Yii::$app->label->title('view', 'Dcs Payment Cycles');
?>
<div class="tbl-dcs-payment-cycle-view">

    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="table-responsive">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view'],
                    'attributes' => [
                        'dcs_payment_cycle_code',
                        'dcsCode.dcs_name',
                        [
                            'attribute' => 'from_date',
                            'format' => 'html',
                            'value' => Yii::$app->controls->view_date($model->from_date)
                        ],
                        'from_shift',
                        [
                            'attribute' => 'to_date',
                            'format' => 'html',
                            'value' => Yii::$app->controls->view_date($model->to_date)
                        ],
                        'to_shift',
                        'interval_value',
                        [
                            'attribute' => 'is_billing',
                            'label' => 'Billing',
                            'format' => 'html',
                            'value' => $model->is_billing == 1 ? 'Yes' : 'No'
                        ],
                        [
                            'attribute' => 'lock_billing_process',
                            'format' => 'html',
                            'value' => $model->lock_billing_process == 1 ? 'Yes' : 'No'
                        ],
                        [
                            'attribute' => 'is_active',
                            'label' => 'Status',
                            'format' => 'html',
                            'value' => GeneralFunctions::getRecordStatus($model->is_active)
                        ],
                    ],
                ])
                ?>
            </div>

        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>             
</div>
