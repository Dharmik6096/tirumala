<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use webvimark\modules\UserManagement\models\User;
use kartik\date\DatePicker;
use kartik\detail\DetailView;
?>

<div class="modal modal-default fade in" id="AssignComplaintModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Assign Complaint') ?></h4>
            </div>
            <div class="popup-header col-sm-12">
                <div class="popup-info">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="table-responsive">
                <?php
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'union_code',
                                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'mcc_plant_code',
                                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'dcs_code',
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'contact_person',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'mobile_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'complain_type_code',
                                'value' => isset($model->complainFors) ? $model->complainFors->complain_type : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'complain_datetime',
                                'value' => Yii::$app->controls->view_date($model->complain_datetime),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'affects_data',
                                'value' => $model->affects_data == 1 ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'physical_damage',
                                'value' => $model->physical_damage == 1 ? 'Yes' : 'No',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'asset_code',
                                'value' => isset($model->asset) ? $model->asset->asset_name : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'complain_status',
                                'value' => Yii::$app->dropdown->getRecords('complain_status')['data'][$model->complain_status],
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'serial_number',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'remarks',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                ];

// View file rendering the widget
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                    'mode' => 'view',
                    'bordered' => true,
                    'striped' => false,
                    'responsive' => true,
                    'hAlign' => 'left',
                    'vAlign' => 'top',
                    'deleteOptions' => [// your ajax delete parameters
                        'params' => ['id' => 1000, 'kvdelete' => true],
                    ],
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
            <div class='row pad-10'>
                <div class="col-sm-12">
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'complaint-assignment-form',
                                ],
//                                'action' => Url::to(['assign-complain'])
                    ]);
                    ?>
                    <div class="show-error"></div>

                    <!--                    <div class="col-sm-6" >
                                            <? Yii::$app->dropdown->dropdown('assign', $model, $form, '', $model->getAttributeLabel('assign_to'), false, 'user_code'); ?>
                                        </div>-->
                    <!--                    <div class="col-sm-6" >
                    <?php //Html::hiddenInput('location_type', '', ['id' => 'location_type']); ?>
                    <?php // Html::hiddenInput('code', '', ['id' => 'code']); ?>
                                            <? Yii::$app->dropdown->depend_dropdown('assign_to', $model, $form, 'location_type', 'form-group col-sm-12', $model->getAttributeLabel('assign_to'), 'user_code', false, 0, [], FALSE, '', false, false); ?>
                                        </div>-->

                    <?= Html::activeHiddenInput($model, 'complain_code'); ?>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'id' => 'assign-complain']) ?>

                            <?= Html::resetButton('Reset', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php
$script = "
    $(document).ready(function(){
        $('#AssignComplaintModal').modal('toggle'); 
        $(document).on('click','.assign-complain',function(e){
            $('#pageloader').show();
            $('#loadercontent').show();
            var complaint_code= $(this).attr('data-complain_code');
            $.ajax({
                type: 'get',
                url: '" . Url::to(['assign-complain']) . "',
                data:{'complain_code':complain_code},
                success: function(data) {     
                    $('#AssignComplaint').html(data);
                    $('#AssignComplaintModal').modal('toggle'); 
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                },    
                error: function(data) {    
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            });
        });
    });";
$this->registerJs($script, View::POS_END, 'assign-complaint');
?>