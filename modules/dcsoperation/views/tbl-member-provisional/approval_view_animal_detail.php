<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use app\components\ActiveForm;

$this->title = 'Member Approval View - Animal & Commitment Details';
?>

<?=
$this->render('approval_view_tabs', [
    'currentStep' => $currentStep,
    'processModel' => $processModel
]);
?>

<div class="panel panel-default panel-main">
    <div class="panel-body">

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-animal-detail')) { ?>
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Animal Details') ?></h4>
            </div>

            <div class="col-sm-12">
                <?=
                $this->render('_animal_grid', [
                    'animalMemberModel' => $animalMemberModel,
                    'animalDataProvider' => $animalDataProvider,
                ])
                ?>
            </div>
            <div class="clearfix"></div>
            <div class="table-responsive">
                <?php
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'animal_type_code',
                                'value' => Yii::$app->general->getforeignkey($model->animalTypeCode, 'animal_type_name'),
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'no_of_buffalo',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'no_of_cow_cross',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'no_of_cow_ind',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'home_consumption_milk',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'market_surplus_milk',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                ];
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                    'mode' => 'view',
                    'bordered' => true,
                    'striped' => false,
                    'responsive' => true,
                    'hAlign' => 'left',
                    'vAlign' => 'top',
                    'enableEditMode' => false,
                    'panel' => false,
                ]);
                ?>
            </div>
        <?php } ?>

        <?php if (Yii::$app->general->checkAccess('/dcsoperation/tbl-member-provisional/approval-view-commitment-detail')) { ?>
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10 margin-top-10">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Commitment Details') ?></h4>
            </div>
            <div class="clearfix"></div>
            <div class="table-responsive">
                <?php
                $attributes = [
                        [
                        'columns' => [
                                [
                                'attribute' => 'total_land',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                                [
                                'attribute' => 'annual_milk_pour',
                                'valueColOptions' => ['style' => 'width:30%']
                            ],
                        ],
                    ],
                        [
                        'columns' => [
                                [
                                'attribute' => 'member_class',
                                'value' => isset($model->member_class) ? Yii::$app->dropdown->getRecords('member_class')['data'][$model->member_class] : '',
                                'valueColOptions' => ['style' => 'width:80%']
                            ],
                        ],
                    ],
                ];
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                    'mode' => 'view',
                    'bordered' => true,
                    'striped' => false,
                    'responsive' => true,
                    'hAlign' => 'left',
                    'vAlign' => 'top',
                    'enableEditMode' => false,
                    'panel' => false,
                ]);
                ?>
            </div>
        <?php } ?>


        <?php $form = ActiveForm::begin(); ?>
        <div class="row">           
            <div class="col-sm-12 margin-top-10">
                <div class="form-group">
                    <?php if ($isLastStep) { ?>
                        <div class="col-md-12 padding_10_0 theme-box mt10">
                            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix margin-bottom-10">
                                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Add Approval Detail') ?></h4>
                            </div>
                            <div class="form-grid">
                                <div class="col-sm-12">
                                    <?php echo $form->errorSummary($processModel); ?>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <?= Yii::$app->dropdown->dropdownStatic('provisional_approval_status', $processModel, $form, '', $processModel->getAttributeLabel('status'), false, 'status', FALSE, FALSE, FALSE); ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?= $form->field($processModel, 'remarks')->textarea(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">
                            <?php
                            echo Html::hiddenInput('reroute_remarks', '', ['id' => 'reroute_remarks']);
                            echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']);
                            if ($isLastStep) {
                                echo Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut reroute', 'data-toggle' => 'modal', 'data-target' => '#ProvisionalModal',]);
                            }

                            $btnLabel = $isLastStep ? 'save' : 'Save & Next';
                            echo Yii::$app->controls->save($btnLabel, $processModel);
                            ?>

                            <?php
                            $prevStep = Yii::$app->controller->getPreviousStepUrl($currentStep, $processModel->process_approval_code, true);
                            if ($prevStep) {
                                ?>
                                <a href="<?= Url::to(['/dcsoperation/tbl-member-provisional/' . $prevStep[0], 'id' => $prevStep['id']]) ?>" class="btn btn-default">Previous</a>
                            <?php } ?>
                        </div>  
                    </div>
                </div>  
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?=
$this->render('@app/modules/document/views/tbl-attachment/_reroute', [
    'model' => $model,
])
?>
