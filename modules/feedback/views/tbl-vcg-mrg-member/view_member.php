<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'VCG MRG Member');
?>
<div class="panel panel-default panel-grid hide-grid-settings">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'member_code',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'member_code',
                            'label' => Yii::t('app', 'Member Name'),
                            'value' => Yii::$app->general->getforeignkey($model->memberCode, 'member_name'),
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'member_tr_code',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'transaction_date',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'wef_date',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'end_date',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'type',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                        [
                            'attribute' => 'status',
                            'valueColOptions' => ['style' => 'width:30%'],
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'remark',
                            'valueColOptions' => ['style' => 'width:80%'],
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
            ]);
            ?>
            <div class="attachment">
                <h2 class="attachment_head">Attachments</h2>
                <div class="attachment_section">
                    <?php
                    $attachments = $model->attachmentCode;
                    if (!empty($attachments)) {
                        foreach ($attachments as $attachment) {
                            $attachmentPath = $attachment->attachment;
                            $extension = pathinfo($attachmentPath, PATHINFO_EXTENSION);
                            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                echo '<div class="image_section">' . Html::img($attachmentPath, [
                                    'alt' => 'Attachment',
                                    'style' => 'max-width:100%; height:auto;',
                                ]) . '</div>';
                            } else {
                                echo 'Attachment is not an image.';
                            }
                        }
                    } else {
                        echo 'No Attachment';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>