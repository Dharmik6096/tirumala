<?php
use yii\web\View;
use yii\helpers\Url;
?>
<div class="col-sm-12 margin-bottom-10">
    <div class="plant-widget-header dashboardWidgetHeader col-sm-12"><?= Yii::t('app', 'Vehicle Wise Tanker Status'); ?>
        <div id="VehicleWiseTankerStatus" class="button_info"><i class="fa fa-info-circle"></i></div>
    </div>
    <div class="flt">
        <div id="vehicle_wise_tanker_status" class="cont milk-collection"></div>
    </div>
    <div class="modal fade" id="VehicleWiseTankerStatusModal" tabindex="-1" role="dialog" aria-labelledby="VehicleWiseTankerStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog width75" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="VehicleWiseTankerStatusModalLabel"><?= Yii::t('app', 'Vehicle Wise Tanker Status'); ?> - <?= date("d-m-Y", strtotime($date)); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body overflow_auto" id="vehicle_wise_tanker_status_table">
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
$('#VehicleWiseTankerStatus').on('click', function() {
    var blockDataString = $('#collapse1 form').serialize();
    var id= 'sp_portal_dashboard_plant_wise_tanker_stage_time'; 
    var union= '" . $union . "';
    var data_type = 1;
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/site/vehicle-wise-tanker-status']) . "',
            data: blockDataString+'&sp='+id+'&union='+union,
            success: function(data) {
            console.log(data);
                var obj1 = data;
                if (obj1.status == 'success') {
                  $('#vehicle_wise_tanker_status_table').html(obj1.vehicle_wise_tanker_status);
                  $('#VehicleWiseTankerStatusModal').modal('show');
                }
            },
            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
            }
        });
});

";
$this->registerJs($script, View::POS_READY, 'vehicle_wise_tanker_status');
?>