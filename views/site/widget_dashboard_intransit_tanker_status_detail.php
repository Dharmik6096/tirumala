<?php
$this->title = 'Dashboard';

use yii\web\View;
use yii\helpers\Url;
?>

<div class="col-sm-12">
    <div class="cal-header dashboardWidgetHeader col-sm-12"><?= Yii::t('app', 'Intransit Tanker Status Detail'); ?>
        <div id="IntransitTankerStatusDetail" class="button_info"><i class="fa fa-info-circle"></i></div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-4">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Waiting For Loading') ?></p>
            <h4 class="dash_block_value block_value" id="Waiting_for_loading">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-4">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Loading Completed') ?></p>
            <h4 class="dash_block_value block_value" id="Loading_Completed">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-4">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Total') ?></p>
            <h4 class="dash_block_value block_value" id="Total">0</h4>
        </div>
    </div>
    <div class="modal fade" id="IntransitTankerStatusDetailModal" tabindex="-1" role="dialog" aria-labelledby="IntransitTankerStatusDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="IntransitTankerStatusDetailModalLabel"><?= Yii::t('app', 'Intransit Tanker Status Detail'); ?> - <?= date("d-m-Y", strtotime($date)); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body overflow_auto" id="intransit_tanker_status_detail_table">
                    <!-- Table content will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = "

$('#IntransitTankerStatusDetail').on('click', function() {
    var blockDataString = $('#collapse1 form').serialize();
    var id= 'sp_portal_dashboard_plant_intransit_tanker_status_detail'; 
    var union= '" . $union . "';
    var data_type = 1;
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/site/intransit-tanker-status-detail']) . "',
            data: blockDataString+'&sp='+id+'&union='+union+'&data_type='+data_type,
            success: function(data) {
                var obj1 = data;
                if (obj1.status == 'success') {
                  $('#intransit_tanker_status_detail_table').html(obj1.intransit_tanker_status_detail);
                  $('#IntransitTankerStatusDetailModal').modal('show');
                }
            },
            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
            }
        });
});

";
$this->registerJs($script, View::POS_READY, 'intransit_tanker_status_detail');
?>