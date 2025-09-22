<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$url = Url::to(['update-status']);
?>
<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => FALSE],
        [
        'attribute' => 'from_date', 'filter' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->from_date);
        },
    ],
        ['attribute' => 'from_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->fromShift, 'shift');
        }, 'filter' => FALSE],
        [
        'attribute' => 'to_date', 'filter' => true,
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->to_date);
        },
    ],
        ['attribute' => 'to_shift', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->toShift, 'shift');
        }, 'filter' => FALSE],
        [
        'attribute' => 'is_weight_manual', 'filter' => false,
        'value' => function($model) {
            return ($model->is_weight_manual == 1) ? 'Yes' : 'No';
        }
    ],
        [
        'attribute' => 'is_quality_manual', 'filter' => false,
        'value' => function($model) {
            return ($model->is_quality_manual == 1) ? 'Yes' : 'No';
        }
    ],
        [
        'attribute' => 'status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('dcs_manual_collection_range_status', $searchModel, 'status'),
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('dcs_manual_collection_range_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('dcs_manual_collection_range_status')['data'][$model->status] : 'N/A';
        }
    ],
        [
        'attribute' => 'request_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('dcs_manual_collection_range_request_type', $searchModel, 'request_type'),
        'value' => function ($model) {
            return isset(Yii::$app->dropdown->getRecords('dcs_manual_collection_range_request_type')['data'][$model->request_type]) ? Yii::$app->dropdown->getRecords('dcs_manual_collection_range_request_type')['data'][$model->request_type] : 'N/A';
        }
    ],
        [
        'attribute' => 'remark', 'filter' => false,
        'value' => function($model) {
            return $model->remark;
        }
    ],
];

$grid_option = [
    'id' => 'customer-deactivation-list',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
//        'view' => TRUE,
        'update' => FALSE,
        'status' => function ($url, $model) {
            $id = $model->manual_collection_code;
            $type = 'DCS';
            $class = $model->status == 1 ? '' : 'link-disable disabled';
            $options = [
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'data-original-title' => 'Status',
                'class' => 'update-status' . $class,
                'data-val' => $id,
                'data-model-id' => $id,
                'data-name' => $type,
                'onclick' => 'openStatusPopup(event, this)',
            ];
            return GhostHtml::a_alert('<i class="fa fa-info-circle"></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
$form = ActiveForm::begin();
?>
<div class="modal fade" id="status-popup-modal" tabindex="-1" role="dialog" aria-labelledby="status-popup-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="status-popup-modal-label">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?= Yii::$app->dropdown->dropdownStatic('dcs_manual_collection_range_status', $model, $form, 'form-group', 'Status', false, 'status', false); ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="remark-input" style="font-size: 10px !important; white-space: nowrap;">Remark</label>
                            <textarea class="form-control" id="remark-input" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveStatus()">Save</button>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    function openStatusPopup(event, element) {
        event.preventDefault();
        var modelId = $(element).data('model-id');
        $('#status-popup-modal').data('model-id', modelId);
        $('#tblallowdcsmanualcollectionrange-status').val('').trigger('change');
        $('#remark-input').val('');
        $('#status-popup-modal').modal('show');
    }
    function saveStatus() {
        var selectedStatus = $('#tblallowdcsmanualcollectionrange-status').val();
        var remark = $('#remark-input').val();
        var modelId = $('#status-popup-modal').data('model-id');
        $.ajax({
            url : '{$url}',        
            type: 'POST',
            data: {
                id: modelId,
                status: selectedStatus,
                remark: remark
            },
            success: function (response) {
                $('#status-popup-modal').modal('hide');
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error('Update failed:', error);
            }
        });
    }";
$this->registerJs($script, View::POS_END, 'dcs-manual-collection-range-status-update');
?>