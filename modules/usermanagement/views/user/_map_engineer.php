<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$title = Yii::$app->label->title('create', 'Enginner Mapping');
$button = Yii::$app->label->button('create');

$this->title = Yii::t('app', $title);


?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">Enginner Mapping For :: <?= Yii::$app->general->getforeignkey($searchModel->userCode, 'name') ?></div>

    <div class="panel-body">
        <?php
        $form = ActiveForm::begin(['options' => [
                        'class' => 'save-form',
                    ],
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => false,
                    'validateOnSubmit' => false,
        ]);
        ?>
        <div class="row theme_border_left theme_border_right theme_border_bottom">
            <div class="col-md-12 padding_10_0 theme-box ">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                    <h5 class="theme-box-heading"><?php echo Yii::t('app', $title); ?></h5>
                </div>
                <?php echo $form->errorSummary($model); ?>
                <div class="col-sm-12 margin-top-10">
                    <div class="col-sm-6  margin-bottom-10">
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
                        echo $form->field($model, 'engineer_id')->checkboxList(
                                $engineer_data, [
                            'id' => 'bmc-list',
                            'class' => 'row mb15',
                            'item' =>
                            function ($index, $label, $name, $checked, $value) {

                                return "<div class='col-sm-2 checklist data-checklist'><div class='checkbox'>" . Html::checkbox($name, $checked, [
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
            </div>
            <?php ActiveForm::end(); ?>
            <?php
            echo $this->render('_mapped_engineer', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>

    <?php
    echo $this->render('@app/components/views/mapping_checkbox_script');
    ?>