<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\grid\GridView;
use yii\web\View;

//$this->title = Yii::$app->label->title('view', 'Reports');
$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : '');
$inclass = !empty($result) ? '' : 'in';
$model->from_date = empty($model->from_date) ? date('d-m-Y') : $model->from_date;
$model->to_date = empty($model->to_date) ? date('d-m-Y') : $model->to_date;
?>
<div class="panel panel-default panel-grid panel-main">

    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <?php
    $removeExportType = [];
    $exportEvents = [];
    if (isset($data['export_title']) && $data['export_title'] && !empty($result)) {
        $report_type = ($model->report_type == 0) ? Yii::t('app', 'VM') : Yii::t('app', 'WQ');
        $this->title = $model->bmc_code . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($model->from_date)) . '_' . $model->from_shift;
        $removeExportType = ['CSV'];
        $exportEvents = ['onRenderSheet' => function($sheet, $widget) {
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setPassword("password");
            },];
    }
    ?>
    <div class="panel-body">
        <div class="report-area">
            <div class="table-responsive mt10 panel-collapse collapse <?= $inclass ?>" id="panel1">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'id' => 'report-form',
                                'field-class' => 'form-group col-sm-3'
                            ],
                            'method' => 'get',
                            'validateOnBlur' => FALSE,
                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>    
                <?php //Yii::$app->dropdown->federation($model, $form, 'federation_code', false); ?>  
                <?php
                $param = isset($data['param']) ? explode(',', $data['param']) : [];
                foreach ($param as $key => $value) {
                    $value_array = explode(':', $value);
                    $value = $value_array[0];

                    if (isset($value_array[1]) && $value_array[1] == 'string') {
                        ?>
                        <div class="col-sm-3">
                            <?php
                            echo Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-2 padding-left-5 padding-right-5', false);
                            ?>
                        </div>    
                        <?php
                        if (isset($value_array[2])) {
                            ?>
                            <div class="col-sm-3 shift">
                                <?php
                                echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel($value_array[2]), false, $value_array[2]);
                                ?>
                            </div>    
                            <?php
                        }
                    }
                    if (in_array($value, array('union_code'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                        </div>   <?php
                    }
                    if (in_array($value, array('mcc_code'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->union_plant($model, $form, 'reportsmodel-union_code', 'plant_code', 'Plant code'); ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'reportsmodel-plant_code', $value, 'MCC'); ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('bmc_code'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'reportsmodel-mcc_code', 'bmc_code', 'BMC'); ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('dcs_code'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->bmc_society($model, $form, 'reportsmodel-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
                        </div>
                        <?php
                    }
                }

                if (isset($data['report_type'])) {
                    echo $form->field($model, 'report_type', [ 'options' => ['class' => 'form-group col-sm-3']])->dropDownList($data['report_type'], ['prompt' => Yii::t('app', 'Select Type')]);
                }
                ?>

                <div class="col-sm-3 mt25">
                    <?php
                    if ($param) {
                        echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'html', 'id' => 'html']);
                    }
                    ?>
                </div>
            </div>
            <?php if (!empty($result) && !(isset($data['download_only']))) { ?>

                <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="fa fa-search"></i></a>
                </div>
            <?php } ?>
            <?php
            if (!empty($result) && !(isset($data['download_only']))) {
                $attr = [];
                foreach ($result[0] as $att => $value) {
                    $attr_arr = [];
                    $attr_arr['attribute'] = $att;
                    $attr[] = $attr_arr;
                }
                $grid_option = [
                    'id' => 'mis-report-list',
                    'attributes' => $attr,
                    'active_column' => false,
                ];

                Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['index'], true, $removeExportType, $exportEvents);
            }
            ?>
        </div>
    </div>
</div>       
<?php ActiveForm::end(); ?>
