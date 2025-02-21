<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\JsExpression;

$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', Yii::$app->label->title('create', 'Dock Mapping'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">
        <div class="panel-heading">
            <?= Html::encode($this->title) . ' : ' . $model->name ?>
        </div>

        <div class="col-md-12 padding_10_0 theme-box mt10">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Add Dock Mapping') ?></h4>
            </div>
            <div class="form-grid">
                <div class="col-sm-12">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'dock-mapping',
                                'validateOnBlur' => FALSE,
                                'validateOnChange' => FALSE,
                                'enableClientValidation' => true,
                                'validateOnSubmit' => true,
                                'fieldConfig' => [],
                    ]);
                    ?>
                    <?php echo $form->errorSummary($doc_mapp_model); ?>
                    <div class="row">
                        <div class="col-sm-2">
                            <?= Yii::$app->dropdown->federation_union($doc_mapp_model, $form, 'union_code', 'Union'); ?>    
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($doc_mapp_model, 'dock_no')->textInput() ?>
                        </div>
                        <div class="col-sm-2">
                            <?= $form->field($doc_mapp_model, 'dock_name')->textInput() ?>
                        </div>
                        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                            <div class="form-group">
                                <?= Yii::$app->controls->save($button, $doc_mapp_model); ?>
                                <?= Yii::$app->controls->reset(); ?>
                                <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?>
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
                    $this->render('_plant_dock_grid', [
                        'dockdataProvider' => $dockdataProvider,
                        'docksearchModel' => $docksearchModel,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>