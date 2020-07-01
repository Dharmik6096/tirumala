<?php

use kartik\widgets\ActiveForm;

$this->title = Yii::t('app', 'File Data Preview');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="not_ellipsis hide_filters_only hide-grid-settings">
            <?php
            $attribute = [
                ['attribute' => 'vlccid'],
                ['attribute' => 'farmerid'],
                [
                    'attribute' => 'dtdate',
                    'value' => function($model) {
                        return Yii::$app->controls->view_date($model->dtdate);
                    }],
                ['attribute' => 'shift'],
                [
                    'attribute' => 'sampletime',
                    'value' => function($model) {
                        return Yii::$app->controls->view_time($model->sampletime);
                    }],
                ['attribute' => 'milktype'],
                ['attribute' => 'qty'],
                ['attribute' => 'fat'],
                ['attribute' => 'snf'],
                ['attribute' => 'water'],
                ['attribute' => 'rate'],
                ['attribute' => 'amt'],
                ['attribute' => 'sampleno'],
                ['attribute' => 'qtymode'],
                ['attribute' => 'farmername'],
                ['attribute' => 'txflag'],
                ['attribute' => 'file_name', 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->fileName, 'file_name');
                    }],
                ['attribute' => 'line_no'],
                ['attribute' => 'response_msg']
            ];
            $grid_option = [
                'id' => 'file-preview-list-grid',
                'attributes' => $attribute,
                'active_column' => false,
                'default_sorting' => FALSE,
            ];

            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['#'], FALSE);
            ?>

        </div>
    </div>
    <div class="panel-footer" >
        <?php
        $form = ActiveForm::begin([
                    'id' => 'file-preview',
                    'validateOnBlur' => TRUE,
                    'validateOnEnter' => TRUE,
                    'validateOnChange' => TRUE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        ?>
        <?php
        if (!empty($dataProvider->getModels())) {
            echo Yii::$app->controls->save('Confirm', $model);
        }
        ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'create'); ?> 
        <?php ActiveForm::end(); ?>
    </div>
</div>
