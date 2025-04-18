<?php

use yii\helpers\Html;
use app\components\ActiveForm;

$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', Yii::$app->label->title('create', 'Vehicle Compartment'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">
        <div class="panel-heading">
            <?= Html::encode($this->title) . ' : ' . $model->parsing_no ?>
        </div>

        <div class="col-md-12 padding_10_0 theme-box mt10">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Add Vehicle Compartment') ?></h4>
            </div>
            <div class="form-grid">
                <div class="col-sm-12">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'compartment-detail',
                                'validateOnBlur' => FALSE,
                                'validateOnChange' => FALSE,
                                'enableClientValidation' => true,
                                'validateOnSubmit' => true,
                                'fieldConfig' => [],
                    ]);
                    ?>
                    <?php echo $form->errorSummary($comp_detail_model); ?>
                    <div class="row">
                        <div class="col-sm-2">
                            <?= $form->field($comp_detail_model, 'compartment_no')->textInput() ?>
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($comp_detail_model, 'capacity')->textInput() ?>
                        </div>
                        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                            <div class="form-group">
                                <?= Yii::$app->controls->save($button, $comp_detail_model); ?>
                                <?= Yii::$app->controls->reset(); ?>
                                <?= Yii::$app->controls->custombutton('Cancel', 'index', '', 'btn-login'); ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box hide-grid-settings">
                <div class="col-sm-12">
                    <?=
                    $this->render('_compartment_detail_grid', [
                        'detaildataProvider' => $detaildataProvider,
                        'detailsearchModel' => $detailsearchModel,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>