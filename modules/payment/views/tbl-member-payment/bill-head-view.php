<?php

use yii\web\View;

$society_name = !empty($dataProvider->getModels()) ?
        ' of ' . Yii::$app->general->getforeignkey($searchModel->dcsCode, 'dcs_name') . ' (' .
        $searchModel->dcs_code . ')' : '';
?>
<div class="modal modal-default fade" id="BillHeadModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Bill Head Detail') . $society_name ?></h4>
            </div>
            <div class="popup-header bg_white">
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
