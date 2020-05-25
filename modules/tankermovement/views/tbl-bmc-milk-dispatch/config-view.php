<?php

use yii\web\View;
?>
<div class="hide_toolbar_only hide_filters_only">

    <div class="modal modal-default fade" id="ConfigModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                    <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Config Result Detail') ?></h4>
                </div>
                <div class="popup-header bg_white">
                    <?php
                    $attribute = [
                        ['attribute' => 'config_code', 'value' => function($model) {
                                return Yii::$app->general->getforeignkey($model->configCode, 'config_name');
                            }
                        ],
                        ['attribute' => 'config_result',
                            'value' => function($model) {
                                return isset($model->configResultCode->config_result) ? $model->configResultCode->config_result : $model->config_result;
                            },]
                    ];
                    $grid_option = [
                        'id' => 'config-detail-list',
                        'attributes' => $attribute,
                        'active_column' => FALSE,
                    ];
                    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>