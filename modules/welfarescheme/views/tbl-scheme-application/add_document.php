<?php

use app\modules\welfarescheme\models\TblSchemeApplicationDocuments;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <ul class="progressbar">
            <li class="inactive">Scheme Application > </li>
            <li>  Upload Scheme Documents</li>
        </ul>
    </div>
    <div class="panel-body">
        <?php
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
            <?php foreach ($doc_model as $key => $doc) { ?>   
                <div class="col-sm-6">
                    <?= $doc->doc_name; ?>
                </div>
                <div class="col-sm-6" >
                    <?= Html::activeHiddenInput($doc, '[' . $key . ']app_doc_id'); ?>
                    <?= Html::activeHiddenInput($doc, '[' . $key . ']application_id'); ?>
                    <?= Html::activeHiddenInput($doc, '[' . $key . ']scheme_id'); ?>
                    <?= Html::activeHiddenInput($doc, '[' . $key . ']doc_id'); ?>

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
                    <?php echo $form->field($doc, '[' . $key . ']file_name')->fileInput()->label(FALSE); ?>
                </div>
                <div class="clearfix"></div>
            <?php } ?>
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('SAVE', $model); ?>
                    <?= Yii::$app->controls->reset(); ?>
                    <?= Yii::$app->controls->cancel($model); ?>
                </div>  
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

