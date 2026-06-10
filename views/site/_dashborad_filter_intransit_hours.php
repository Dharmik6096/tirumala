<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\widgets\Select2;

$hours_types = [
        'empty' => 'Empty',
        'cc_waiting' => 'CC Waiting',
        'load' => 'Load',
        'round_trip' => 'Round Trip'
];
$display_types = ['count' => 'Count', 'percentage' => 'D%'];
$sessionPlant = Yii::$app->session->get('Plant');
$plantCodesStr = is_array($sessionPlant) ? implode(',', $sessionPlant) : (!empty($sessionPlant) ? $sessionPlant : 0);

$model->from_date3 = empty($model->from_date3) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->from_date3;
$model->to_date3 = empty($model->to_date3) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->to_date3;

$id1 = !empty($range_id1) ? $range_id1 : 'intransit_dt1';
$id2 = !empty($range_id2) ? $range_id2 : 'intransit_dt2';
$unionCode = !empty($model->union_code) ? $model->union_code : '';
?>
<div class="modal fade" id="modal_<?= $table_class ?>" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= $popup_title ?></h4>
            </div>
            <div class="modal-body dashboard_controls">
                <?php $form = ActiveForm::begin(['action' => 'javascript:void(0)', 'id' => $id]); ?>

                <div class="<?= $date_range_class ?>">
                    <?php if ($date_range) { ?>
                        <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date3', 'to_date3', $id1, $id2); ?>
                    <?php } ?>
                    <?= Html::activeHiddenInput($model, 'union_code'); ?>
                </div>

                <div class="col-sm-3">
                    <?= Select2::widget([
                            'name' => 'hours_type',
                            'data' => $hours_types,
                            'value' => 'Empty Tanker',
                            'options' => ['id' => 'intransit-hours-type-' . $id],
                    ]); ?>
                </div>

                <div class="col-sm-2">
                    <?= Select2::widget([
                            'name' => 'display_type',
                            'data' => $display_types,
                            'value' => 'count',
                            'options' => ['id' => 'intransit-display-type-' . $id],
                    ]); ?>
                </div>

                <div class="col-sm-2 pt25">
                    <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, 'btn_' . $id . ' dashboardSearchButton'); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<button type="button" class="widget_table_search_btn" data-toggle="modal" data-target="#modal_<?= $table_class ?>"><i class="fa fa-search"></i></button>
<?php
$script = "
    $(document).ready(function () {
        setIntransitHoursTab(); 
        
        $('#$id').on('submit', function(e) {
            e.preventDefault();
            setIntransitHoursTab();
            $('#modal_$table_class').modal('hide');
            return false;
        });

        $('.btn_$id').on('click', function(e) {
            e.preventDefault();
            $('#$id').submit();
        });
        
        function setIntransitHoursTab(){  
            var from_date = $('#$id1').val();
            var to_date = $('#$id2').val();
            var union = '" . $unionCode . "';
            var h_type = $('#intransit-hours-type-$id').val(); 
            var d_type = $('#intransit-display-type-$id').val(); 
             
            if(from_date != '' && to_date != ''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['intransit-hours-tab']) . "',
                    data: {
                        'from_date' : from_date, 
                        'to_date' : to_date, 
                        'union' : union,
                        'hours_type' : h_type,
                        'representation_type' : d_type
                    },
                    success: function(data) {                                        
                        $('#" . $id . "_container').html(data);
                        
                        var container = $('#intransit_hours_container');
    container.html(data);

    // 2. Find the title in the data attribute and apply it to the header
    var updatedTitle = container.find('.intransit-data-wrapper').data('new-title');
    
    if (updatedTitle) {
        $('#intransit_title').html(updatedTitle);
    }
                    },
                    error: function(xhr) {
                        console.log('Intransit Error: ' + xhr.statusText);
                    }
                });   
            }
            return false;
        }
    });
";
$this->registerJs($script, View::POS_READY, 'js_' . $id);
?>
