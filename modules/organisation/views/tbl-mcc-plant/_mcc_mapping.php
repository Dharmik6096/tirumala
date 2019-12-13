<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$title = Yii::$app->label->title('create', 'MCC Mapping');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">MCC Mapping For :: <?= $searchModel->mcc_plant_code . ' - ' . Yii::$app->general->getforeignkey($searchModel->mainMccCode, 'name') ?></div>

    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'save-form',
                    ],
                    'validateOnBlur' => false,
                    'validateOnEnter' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => false,
        ]);
        ?>
        <h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-6">
                <div class="btn-group">
                    <span class="input-group-btn">
                        <span id="show-only-selected-data" class="btn btn-default btn-sm">
                            <i class="fa fa-minus"></i> Show only selected
                        </span>
                        <span id="show-all" class="btn btn-default hide btn-sm">
                            <i class="fa fa-plus"></i> Show all
                        </span>
                    </span>
                </div>
            </div>

            <div class="col-sm-12">
                <?php
                echo $form->field($model, 'p_mcc_plant_code')->checkboxList(
                        $mcc_data, [
                    'id' => 'mcc-list',
                    'class' => 'row mb15',
                    'item' =>
                    function ($index, $label, $name, $checked, $value) {
                        return "<div class='col-sm-4 checklist data-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
                                    'value' => $value,
                                    'label' => '<label for=' . $value . '>' . $label . '</label>',
                                    'labelOptions' => [
                                        'class' => 'data-text',
                                    ],
                                    'class' => 'data-checkbox',
                                    'id' => $value,
                                ]) . "</div></div>";
                    },])->label(false);
                        ?>

                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">                    
                            <?= Yii::$app->controls->save($button, $model); ?>
                            <?= Yii::$app->controls->reset(); ?>
                            <?= Yii::$app->controls->cancel($model); ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <?php
                echo $this->render('_mapped_mcc', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
        <?php
        echo $this->render('@app/components/views/mapping_checkbox_script');
        ?>