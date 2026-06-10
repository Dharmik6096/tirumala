<?php
$this->title = 'Dashboard';

use yii\web\View;
use yii\helpers\Url;
?>

<div class="col-sm-12">
    <a href="<?= Url::to(['site/mcc-complain-list']) ?>" target="_blank">
        <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2 hover_effect_class_if_any">
            <div class="div_mobile_dash_block_content">
                <p class="mobile_dash_block_header"><?= Yii::t('app', 'Total Complain') ?></p>
                <h4 class="dash_block_value block_value" id="total_complain">0</h4>
            </div>
        </div>
    </a>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Created Complain') ?></p>
            <h4 class="dash_block_value block_value" id="created_complain">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Assigned Complain') ?></p>
            <h4 class="dash_block_value block_value" id="assigned_complain">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Inprogress Complain') ?></p>
            <h4 class="dash_block_value block_value" id="inprogress_complain">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Closed Complain') ?></p>
            <h4 class="dash_block_value block_value" id="close_complain">0</h4>
        </div>
    </div>
    <div class="div_dash_block dashboardWidgetDetailPortion col-sm-2">
        <div class="div_mobile_dash_block_content">
            <p class="mobile_dash_block_header"><?= Yii::t('app', 'Resolved Complain') ?></p>
            <h4 class="dash_block_value block_value" id="resolved_complain">0</h4>
        </div>
    </div>
    <button id="addButton" class="btn btn-primary plus-button"><i class="fa fa-plus"></i></button>
    <div class="modal fade" id="complainSummaryModal" tabindex="-1" role="dialog" aria-labelledby="complainSummaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="complainSummaryModalLabel">Complain Summary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="complain_summary_table">
                    <!-- Table content will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="complain_summary_bar_chart" class="margin_top_15_reverse"></div>
</div>

<?php
$script = "

function drowBarChart(cont,text,w_data) {
    var keyArr = [];
    var createdComplain = [];
    var assignedComplain = [];
    var inprogressComplain = [];
    var resolvedComplain = [];
    var closeComplain = []; 
    
    $.each(w_data, function(index, value) {
          keyArr.push(index);
          createdComplain.push(parseInt(value.created_complain));  
          assignedComplain.push(parseInt(value.assigned_complain));
          inprogressComplain.push(parseInt(value.inprogress_complain));  
          resolvedComplain.push(parseInt(value.resolved_complain));  
          closeComplain.push(parseInt(value.close_complain));  
    });
    
    var bar_chart = $('#' + cont);
    if (bar_chart.length) {
        Highcharts.chart(cont,{
            chart: {
                type: 'column'
            },
            title: {
                text: text 
            },
            xAxis: {
                categories: keyArr,
                title: { text: null },
                labels: { color: '#0763a1', fontSize: '14px' }
            },
            yAxis: {
                title: { text: '' },
                labels: { color: '#0763a1', fontSize: '14px' }
            },
//            plotOptions: { column: { pointWidth: 20 } },
            series: [{
                name: 'Created Complain',
                data: createdComplain,
                color: '#ff7f07'
            },{
                name: 'Assigned Complain',
                data: assignedComplain,
                color: '#ffc107'
            },{
                name: 'Inprogress Complain',
                data: inprogressComplain,
                color: '#198754e6'
            },{
                name: 'Resolved Complain',
                data: resolvedComplain,
                color: '#00A3DE'
            },{
                name: 'Close Complain',
                data: closeComplain,
                color: '#0763a1'
            }],
            legend: {
                enabled: true 
            }
        });
    }
}

$('#addButton').on('click', function() {
    $.ajax({
        type: 'POST',
        url: '" . Url::to(['/site/complain-summary-dashboard']) . "',
        success: function(data) {
            if (data.status === 'success') {
                $('#complain_summary_table').html(data.tableHtml);
                $('#complainSummaryModal').modal('show');
            } else {
                alert('Failed to fetch data');
            }
        },
        error: function() {
            alert('Error occurred while fetching data');
        }
    });
});

";
$this->registerJs($script, View::POS_READY, 'complain_summary_bar_chart');
?>