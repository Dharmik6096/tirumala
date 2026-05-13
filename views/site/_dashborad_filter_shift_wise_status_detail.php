<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\widgets\Select2;

$category_type = [ '1' => 'Cell Count', '2' => 'Trips Count'];

$sessionPlant = Yii::$app->session->get('Plant');
$plantCodesStr = is_array($sessionPlant) ? implode(',', $sessionPlant) : (!empty($sessionPlant) ? $sessionPlant : 0);

$model->from_date3 = empty($model->from_date3) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->from_date3;
$model->to_date3 = empty($model->to_date3) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->to_date3;

$id1 = !empty($range_id1) ? $range_id1 : 'shift_dt1';
$id2 = !empty($range_id2) ? $range_id2 : 'shift_dt2';
$unionCode = !empty($model->union_code) ? $model->union_code : '';
?>
<div class="modal fade" id="modal_<?= $table_class ?>" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal">×</button>
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
                            'name' => 'category_type',
                            'data' => $category_type,
                            'value' => '1',
                            'options' => ['id' => 'shift-wise-status-detail-' . $id],
                    ]); ?>
                </div>
                <div class="col-sm-2 pt25">
                    <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, 'btn_' . $id . ' dashboardSearchButton btn-login'); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<button type="button" class="widget_table_search_btn" data-bs-toggle="modal" data-bs-target="#modal_<?= $table_class ?>"><i class="fa fa-search"></i></button>
<?php
$script = "
    $(document).ready(function () {
        setShiftWiseStatusDetailTab(); 
        
        $('#$id').on('submit', function(e) {
            e.preventDefault();
            setShiftWiseStatusDetailTab();
            $('#modal_$table_class').modal('hide');
            return false;
        });

        $('.btn_$id').on('click', function(e) {
            e.preventDefault();
            $('#$id').submit();
        });
        function setShiftWiseStatusDetailTab(){  
            var from_date = $('#$id1').val();
            var to_date = $('#$id2').val();
            var union = '" . $unionCode . "';
            var h_type = $('#shift-wise-status-detail-$id').val(); 
             
            if(from_date != '' && to_date != ''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['shift-wise-status-detail-tab']) . "',
                    data: {
                        'from_date' : from_date, 
                        'to_date' : to_date, 
                        'union' : union,
                        'category_type' : h_type,
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
$this->registerJs($script, View::POS_READY, 'js_' . $id);
?>
