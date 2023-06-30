<?php

use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collection Approve'));
?>
<div class="panel panel-main">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $model]); ?>
            <?php
            $action = Url::to(['get-temp-data']);
            $form = ActiveForm::begin([
                        'id' => 'summary-form',
                        'action' => $action,
                        'method' => 'post']);
            ?>
            <?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
            <?php
            $attr = [
                ['class' => 'kartik\grid\CheckboxColumn',
                    'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                    'checkboxOptions' => function($model) {
                $status = isset($model['is_approved']) ? Yii::$app->dropdown->getRecords('approval_status')['data'][$model['is_approved']] : '';
                $status = !empty($status) && $status == 'Pending' ? false : true;
                return ['value' => $model['dcs_code'] . '::' . $model['date_time_of_collection'] . '::' . $model['shift_code'] . '::' . $model['is_approved'] . '::' . $model['is_updated'], 'disabled' => $status];
            }],
                ['attribute' => 'dcs', 'label' => Yii::t('app', 'DCS'), 'filter' => false],
                ['attribute' => 'date_time_of_collection', 'filter' => false],
                ['attribute' => 'shift', 'filter' => false],
                ['attribute' => 'total_count', 'filter' => false],
                ['attribute' => 'total_qty', 'filter' => false],
                ['attribute' => 'is_approved', 'value' => function ($model) {
                        return isset($model['is_approved']) ? Yii::$app->dropdown->getRecords('approval_status')['data'][$model['is_approved']] : '';
                    }, 'filter' => false],
                ['attribute' => 'is_updated', 'value' => function ($model) {
                        return isset($model['is_updated']) ? Yii::$app->dropdown->getRecords('update_status')['data'][$model['is_updated']] : '';
                    }, 'filter' => false],
            ];
            $grid_option = [
                'id' => 'milk-coll-dcs-list',
                'attributes' => $attr,
                'active_column' => false,
                'actions' => [
                    'deactive' => function ($url, $model) {
//                        $name = $model->member_name;
//                        $class = ($model->is_active == 1) ? '' : 'link-disable';
                        $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View', 'class' => 'view_data', 'data-dcs_code' => $model['dcs_code'], 'data-date_time_of_collection' => $model['date_time_of_collection'], 'data-is_approved' => $model['is_approved'], 'data-shift_code' => $model['shift_code'], 'data-is_updated' => $model['is_updated']];
                        return GhostHtml::a_alert('<i class="fa fa-eye"></i>', ['/collection/tbl-milk-collection-temp/milk-collection-list'], $options);
                    },
                        ],
                    ];
                    Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['get-temp-data'], true);
                    ?>
                    <div class="col-lg-12" >
                        <?= Html::submitButton(Yii::t('app', 'Approve'), ['class' => 'btn btn-default milk-coll-submit', 'name' => 'approve']); ?>
                        <?= Html::submitButton(Yii::t('app', 'Reject'), ['class' => 'btn btn-default milk-coll-submit', 'name' => 'reject']); ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
        <div id="milkCollectionDetails"></div>
        <?php
        $script = "
    $('.milk-coll-submit').on('click',function(){
        $('#flag').val($(this).prop('name'));
       $('form#summary-form').submit();
       $('#pageloader').show();
       $('#loadercontent').show();
    });
    $(document).ready(function(){
    $(document).on('click','.view_data',function(e){
        $('#pageloader').show();
        $('#loadercontent').show();
        var dcs_code= $(this).attr('data-dcs_code');
        var date_time_of_collection= $(this).attr('data-date_time_of_collection');
        var shift= $(this).attr('data-shift_code');
        var is_approved= $(this).attr('data-is_approved');
        var is_updated= $(this).attr('data-is_updated');
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/collection/tbl-milk-collection-temp/milk-collection-list']) . "',
            data:{'dcs_code':dcs_code, 'date_time_of_collection':date_time_of_collection, 'shift' : shift, 'is_approved':is_approved, 'is_updated':is_updated},
            success: function(data) {     
                $('#milkCollectionDetails').html(data);
                $('#crossTabDetailsModal').modal('toggle'); 
                $('#loadercontent').hide();
                $('#pageloader').hide();
            },    
            error: function(data) {    
                $('#loadercontent').hide();
                $('#pageloader').hide();
            }
        });
    });
});
    ";
        $this->registerJs($script, View::POS_END, 'save-coll-data');
        ?>