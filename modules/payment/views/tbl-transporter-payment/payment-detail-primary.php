<?php

use kartik\detail\DetailView;
use kartik\grid\GridView;

if ($model->transporter_type == 0) {
    $info = Yii::t('app', 'Payment Detail of ') . $model->routeCode->route_name . ' (' . $model->transporter_name . ' - ' . $model->parsing_no . ')';
    $file_to_render = '_primary_vehicle_detail';
} else {
    $info = Yii::t('app', 'Payment Detail of ') . $model->transporter_name . ' (' . $model->parsing_no . ')';
    $file_to_render = '_secondary_vehicle_detail';
}
?>
<div class="modal modal-default fade" id="PaymentDetailModal" role="dialog">
    <div class="modal-dialog popup-100-60">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= $info; ?></h4>
            </div>
            <div class="panel panel-default panel-main">
                <div class="panel-heading"><?= $this->title ?></div>
                <div class="panel-body">
                    <div class="popup-header bg_white col-sm-12">
                        <?=
                        $this->render($file_to_render, [
                            'model' => $model,
                            'vehicleDetail' => $vehicleDetail,
                            'headDetail' => $headDetail,
                            'searchModel' => $searchModel,
                            'searchModelHead' => $searchModelHead
                        ])
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

