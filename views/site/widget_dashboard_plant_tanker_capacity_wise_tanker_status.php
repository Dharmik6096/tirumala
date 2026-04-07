<?php
use yii\web\View;
use yii\helpers\Url;
?>


<div class="col-sm-12 margin-bottom-10">
    <div class="plant-widget-header dashboardWidgetHeader col-sm-12"><?= Yii::t('app', 'Plant Tanker Capacity Wise Tanker Status'); ?>
        <div id="PlantTankerCapacityWiseTankerStatus" class="button_info"><i class="fa fa-info-circle"></i></div>
    </div>
    <div class="flt">
        <div id="plant_tanker_capacity_wise_tanker_status" class="cont milk-collection"></div>
    </div>
    <div class="modal fade" id="PlantTankerCapacityWiseTankerStatusModal" tabindex="-1" role="dialog" aria-labelledby="PlantTankerCapacityWiseTankerStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog width75" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PlantTankerCapacityWiseTankerStatusModalLabel"><?= Yii::t('app', 'Plant Tanker Capacity Wise Tanker Status'); ?> - <?= date("d-m-Y", strtotime($date)); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body overflow_auto" style="max-height: 400px;" id="plant_tanker_capacity_wise_tanker_status_table">
                    <!-- Table content will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = "

$('#PlantTankerCapacityWiseTankerStatus').on('click', function() {
    var blockDataString = $('#collapse1 form').serialize();
    var id= 'sp_portal_dashboard_plant_tanker_capacity_wise_tanker_status'; 
    var union= '" . $union . "';
    var data_type = 1;
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/site/plant-tanker-capacity-wise-tanker-status']) . "',
            data: blockDataString+'&sp='+id+'&union='+union+'&data_type='+data_type,
            success: function(data) {
                var obj1 = data;
                if (obj1.status == 'success') {
                  $('#plant_tanker_capacity_wise_tanker_status_table').html(obj1.plant_tanker_capacity_wise_tanker_status);
                  $('#PlantTankerCapacityWiseTankerStatusModal').modal('show');
                }
            },
            error:function(data){
//                                alert('Your data has not been submitted.Please try again');
            }
        });
});

";
$this->registerJs($script, View::POS_READY, 'complain_summary_bar_chart');
?>