<?php

use yii\web\View;
use kartik\grid\GridView;

?>
<div class="modal modal-default fade" id="MemberInstallmentModel" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Member Installment') ?></h4>
            </div>
            <div class="popup-header bg_white">
                <?php
                $attribute = [
                    ['label' => 'Sale Date', 'attribute' => 'invoice_date',
                        'filterType' => GridView::FILTER_DATE,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                                'autoclose' => true]
                        ],
                        'value' => function($model) {
                            return Yii::$app->controls->view_date($model->installment_date);
                        }, 'filter' => false],
                    ['attribute' => 'main_amount', 'filter' => FALSE],
                    ['attribute' => 'installment_amount', 'filter' => FALSE],
                ];
                $grid_option = [
                    'id' => 'bill-head-detail-list',
                    'attributes' => $attribute,
                    'active_column' => FALSE,
                ];
                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "$('.kv-panel-before').hide();$('.filters').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
