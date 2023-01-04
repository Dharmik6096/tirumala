<?php

use kartik\detail\DetailView;
use kartik\grid\GridView;
?>
<div class="modal modal-default fade" id="PaymentDetailModal" role="dialog">
    <div class="modal-dialog popup-100-60">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Payment Detail of ') . $model->routeCode->route_name . ' (' . $model->transporter_name . ' - ' . $model->parsing_no . ')' ?></h4>
            </div>
            <div class="panel panel-default panel-main">
                <div class="panel-heading"><?= $this->title ?></div>
                <div class="panel-body">
                    <div class="popup-header bg_white col-sm-12">
                        <?=
                        $this->render('_primary_vehicle_detail', [
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

