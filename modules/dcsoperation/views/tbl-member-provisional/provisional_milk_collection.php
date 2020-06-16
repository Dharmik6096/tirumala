<?php

use kartik\detail\DetailView;
?>

<div class="modal fade in popup_modal" id="provisionalMilkCollection" role="dialog">
    <div class="modal-dialog w750 hide-grid-settings hide-grid-search">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title" id="modal-title">Provisional Milk Collection Detail</h4>
            </div>
            <div class="modal-body full_width_grid" id="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php
                                if(isset($model->dcs_code)){

                                $attributes = [
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'society_code',
                                                'value' => (string) $model->dcs_code,
                                                'valueColOptions' => ['style' => 'width:30%']
                                            ],
                                            [
                                                'attribute' => 'dcs_code',
                                                'value' => isset($model->dcsCode) ? $model->dcsCode->dcs_name : '',
                                                'valueColOptions' => ['style' => 'width:30%']
                                            ],
                                        ],  
                                    ],
                                    [
                                        'columns' => [
                                            [
                                                'attribute' => 'member_code',
                                                'value' => $model->member_code,
                                                'valueColOptions' => ['style' => 'width:30%']
                                            ],
                                            [
                                                'attribute' => 'name',
                                                'value' => $model->name,
                                                'valueColOptions' => ['style' => 'width:30%']
                                            ],
                                        ],
                                    ],
                                ];

                                echo DetailView::widget([
                                    'model' => $searchModel,
                                    'attributes' => $attributes,
                                    'mode' => 'view',
                                    'bordered' => true,
                                    'striped' => false,
                                    'responsive' => true,
                                    'hAlign' => 'left',
                                    'vAlign' => 'top',
                                    'deleteOptions' => [ // your ajax delete parameters
                                        'params' => ['id' => 1000, 'kvdelete' => true],
                                    ],
                                    'container' => ['id' => 'kv-demo'],
                                ]);
                                }
                                ?>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?=
                                            $this->render('_milk_collection_grid', [
                                                'dataProvider' => $dataProvider,
                                                'searchModel' => $searchModel,

                                            ]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>