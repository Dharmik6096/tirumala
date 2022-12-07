<?php
$this->title = 'Upload Scheme Documents';

use app\modules\welfarescheme\models\TblSchemeApplicationDocuments;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\detail\DetailView;
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <ul class="progressbar">
            <li class="inactive">Scheme Application No. <?= $model->application_id ?>  > </li>
            <li>  Upload Scheme Documents</li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="table-responsive">

            <?php
            $attributes = [
                    [
                    'columns' => [
                            [
                            'attribute' => 'scheme_id',
                            'value' => Yii::$app->general->getforeignkey($model->schemeId, 'scheme_name'),
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'scheme_value',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'customer_name',
                            'value' => isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type) : '',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'customer_type',
                            'value' => isset($model->customer_type) ? (strtolower($model->customer_type) == 'member' ? 'Member' : Yii::$app->general->getforeignkey($model->customerType, 'customer_desc') ) : '',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'ex_code',
                            'value' => isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, true) : '',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'customer_code',
                            'value' => isset($model->customer_type) ? Yii::$app->general->getCustomer($model, $model->customer_type, FALSE, FALSE, true) : '',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'application_date',
                            'value' => Yii::$app->controls->view_date($model->application_date),
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'min_pouring_day',
                            'label' => Yii::t('app', 'Day(min/act)'),
                            'value' => $model->min_pouring_day . '/' . $model->actual_pouring_day,
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'min_pouring_qty',
                            'label' => Yii::t('app', 'Qty(min/act)'),
                            'value' =>
                            $model->min_pouring_qty . '/' . $model->actual_pouring_qty,
                            'valueColOptions' => ['style' => 'width:15%']
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
        <div class="col-md-12 padding_10_0 theme-box">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Document List') ?></h4>
            </div>
            <div class="form-grid">
                <?php
                if (!empty($doc_model)) {
                    $form = ActiveForm::begin([
                                'options' => ['id' => 'create-scheme-document-form',
                                    'enctype' => 'multipart/form-data'
                                ],
                                'validateOnBlur' => false,
                                'validateOnChange' => FALSE,
                                'enableClientValidation' => true,
                                'validateOnSubmit' => true,
                                'fieldConfig' => [
                    ]]);
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-striped table-main table-language table-rate">
                                <tbody>
                                    <?php foreach ($doc_model as $key => $doc) { ?>
                                        <tr>
                                            <td width='60%'><?= $doc->doc_name; ?></td>
                                            <td width='40%' class="hide_help_block">
                                                <?= Html::activeHiddenInput($doc, '[' . $key . ']app_doc_id'); ?>
                                                <?= Html::activeHiddenInput($doc, '[' . $key . ']application_id'); ?>
                                                <?= Html::activeHiddenInput($doc, '[' . $key . ']scheme_id'); ?>
                                                <?= Html::activeHiddenInput($doc, '[' . $key . ']doc_id'); ?>
                                                <?php
                                                $accept = !empty($doc->doc_ext) ? $doc->doc_ext : 'application/pdf,image/jpeg';
                                                echo $form->field($doc, '[' . $key . ']file_name')->fileInput(['accept' => $accept])->label(FALSE);
                                                ?>
                                                <?php
                                                /*   $accept = !empty($master_doc->doc_ex) ? $master_doc->doc_ex : '*';
                                                  echo $form->field($app_doc, '[' . $i . ']file_name')->widget(\kartik\widgets\FileInput::classname(), [
                                                  'options' => ['accept' => $accept],
                                                  'pluginOptions' => [
                                                  'showPreview' => false,
                                                  'showRemove' => FALSE,
                                                  'showUpload' => false,
                                                  ]
                                                  ])->label(FALSE); */
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 shortcut-main mt10" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                            <div class="form-group">
                                <?= Yii::$app->controls->save('SAVE', $model); ?>
                                <?= Yii::$app->controls->reset(); ?>
                                <?= Yii::$app->controls->cancel($model); ?>
                            </div>  
                        </div>
                    </div>
                    <?php
                    ActiveForm::end();
                }
                ?>
            </div>
        </div>
    </div>
</div>

