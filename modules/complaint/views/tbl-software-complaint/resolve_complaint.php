<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use kartik\detail\DetailView;

//Url::remember();

$url = Url::to(['/complaint/tbl-software-complaint/resolve-complaint', 'id' => $model->complaint_code]);
$this->title = 'Software Complaint Resolve';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="row">
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
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'Ref. Code'),
                                'value' => Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'dcs_code',
                                'label' => Yii::t('app', 'Soc. Code'),
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
                                'attribute' => 'contact_person_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'complaint_date',
                                'value' => Yii::$app->controls->view_date($model->complaint_date),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'service_call_no',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'product_code',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'product_name',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'complaint_type',
                                'value' => isset($model->complaint_type) ? Yii::$app->dropdown->getRecords('complaint_type')['data'][$model->complaint_type] : '',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'priority',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'complaint_desc',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'km',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'remarks',
                                'valueColOptions' => ['style' => 'width:80%']
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
            <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
                <div class="col-md-12 padding_10_0 theme-box view-subtitle">
                    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                        <h4 class="theme-box-heading"><?= Yii::t('app', 'Complaint Details') ?></h4>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-grid">
                        <?=
                        $this->render('../../../complaint/views/tbl-software-complaint/_assign_grid', [
                            'model' => $model,
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
            <h4 class="theme-box-heading"><?= Yii::t('app', 'Assign Complaint') ?></h4>
        </div>
        <?php
        $form = ActiveForm::begin([
                    'action' => $url,
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="clearfix"></div>
        <div class="row">
            <?=
            $this->render('_form_resolve_complaint', [
                'model' => $model,
                'txnModel' => $txnModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'form' => $form,
                'showUserdd' => $showUserdd,
            ])
            ?>
            <div class="col-sm-4 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
