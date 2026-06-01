<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\widgets\Select2;

$quality_params = [
        'waiting_tankers' => 'Waiting Tankers',
        'parital_unload_tankers' => 'Partial Unload Tankers',
        'tanker_at_cleaning' => 'Tanker at Cleaning',
        'tanker_at_quality' => 'Tanker at Quality',
        'cleaned_not_assign_trip_vehicles' => 'Cleaned not Assign trip Vehicles',
        'trip_assigned_tankers' => 'Trip Assigned Tankers',
        'total_in_side' => 'Total In Side'
];
$display_types = ['count' => 'Count', 'percentage' => 'D%'];
$sessionPlant = Yii::$app->session->get('Plant');
$plantCodesStr = is_array($sessionPlant) ? implode(',', $sessionPlant) : (!empty($sessionPlant) ? $sessionPlant : 0);

// Set defaults
$model->from_date3 = empty($model->from_date3) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->from_date3;
$model->to_date3 = empty($model->to_date3) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->to_date3;

$id1 = !empty($range_id1) ? $range_id1 : 'cross_tab_dt1';
$id2 = !empty($range_id2) ? $range_id2 : 'cross_tab_dt2';
$unionCode = !empty($model->union_code) ? $model->union_code : '';
$qlt_param = !empty($qlt_param) ? $qlt_param : 'qlt_param';
?>

<div class="modal fade" id="modal_<?= $table_class ?>" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= $popup_title ?></h4>
            </div>
            <div class="modal-body dashboard_controls">
                <?php $form = ActiveForm::begin(['action' => ['index'], 'id' => $id]); ?>

                <div class="<?= $date_range_class ?>">
                    <?php if ($date_range) { ?>
                        <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date3', 'to_date3', $id1, $id2); ?>
                    <?php } ?>
                    <?= Html::activeHiddenInput($model, 'union_code'); ?>
                </div>

                <div class="col-sm-2">
                    <?= $form->field($model, 'qlt_param')->widget(Select2::classname(), [
                            'data' => $quality_params,
                            'options' => ['id' => 'inside-plant-qlt_param-' . $id],
                    ])->label(false); ?>
                </div>

                <div class="col-sm-2">
                    <?= Select2::widget([
                            'name' => 'display_type',
                            'data' => $display_types,
                            'value' => 'count',
                            'options' => ['id' => 'inside-plant-display_type-' . $id],
                    ]); ?>
                </div>

                <div class="col-sm-3 pt5 dashboard_modal_footer">
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
        setInsidePlantTankersTab(); 
        
        $('#$id').on('submit', function(e) {
            e.preventDefault(); // STOP the page from reloading/redirecting
            setInsidePlantTankersTab();
            $('#modal_$table_class').modal('hide');
            return false;
        });

        $('.btn_$id').on('click', function(e) {
            e.preventDefault();
            $('#$id').submit();
        });
        
        function setInsidePlantTankersTab(){  
            var from_date = $('#$id1').val();
            var to_date = $('#$id2').val();
            var union = '" . $unionCode . "';
            var qlt_param = $('#inside-plant-qlt_param-$id').val(); 
            var display_type = $('#inside-plant-display_type-$id').val(); 
             var plant_code = '" . $plantCodesStr . "'; 
             
            if(from_date != '' && to_date != ''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['inside-plant-tankers-tab']) . "',
                    data: {
                        'from_date' : from_date, 
                        'to_date' : to_date, 
                        'union' : union,
                        'tanker_status' : qlt_param,
                        'representation_type' : display_type
                    },
                    success: function(data) {                                        
                        $('#" . $id . "_container').html(data);
                    },
                    error: function(xhr) {
                        console.log('Error: ' + xhr.statusText);
                    }
                });   
            }
            return false;
        }
    });
";
$this->registerJs($script, View::POS_READY, $id);
?>

