<?php

use yii\web\View;

$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : 'Search');
$defaultToggle = true;
$class = 'beforeGridLoad';
if (!empty($result)) {
    $defaultToggle = false;
    if (is_array($result)) {
        $class = '';
    }
}
if (!empty($model->getErrors())) {
    $defaultToggle = true;
}
?>
<div class="panel panel-default panel-grid panel-main hide_grid_search_filter hide_grid_settings_filter">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="report-area not_ellipsis">
            <div class="grid-search large-search hidden-print">
                <?= $this->render('_search', ['model' => $model, 'data' => $data, 'result' => $result, 'dataProvider' => $dataProvider]); ?>
            </div>
            <div class="grid-search search-filter searchBtnReport text-right <?= $class ?>">
                <div class="btn-group btn btn-default mis_report_modal_toggle"><i class="fa fa-search"></i></div>
            </div>
            <div class="clearfix"></div>
            <?= $this->render('_form_grid', ['model' => $model, 'dataProvider' => $dataProvider, 'result' => $result]);
            ?>
        </div>
    </div>
</div>

<?php
$script = "
  $('.save-alert').on('click',function(){
 var checkBoxCount = $('.kv-row-checkbox:checked').length;
 if(checkBoxCount > 0) {
    if(checkBoxCount <= 1000){
       $('#sms-list-save').submit();
    }else {
            bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'You can select maximum 1000 Record') . "</span>');
    }
  } else {
        bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>" . Yii::t('app', 'Please Select atleast one Record') . "</span>');
    }

   
    });    

$('.mis_report_modal_toggle').on('click', function(){
    $('#mis_report_search_filter').modal('toggle');
});";

if ($defaultToggle) {
    $script .= "
        $(document).ready(function () {
            $('#mis_report_search_filter').modal('toggle');
        });
    ";
}
$this->registerJs($script, View::POS_READY, 'mis-report-script');
?>
