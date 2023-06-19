<?php

use yii\bootstrap5\ActiveForm;
use dosamigos\tinymce\TinyMce;

$form = ActiveForm::begin([
            'validateOnBlur' => TRUE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ],['options' => ['enctype' => 'multipart/form-data']]);
?>

<div class="row">
    <div class="col-sm-3">
        <?= $form->field($model, 'title')->textInput() ?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($model, 'description')->widget(TinyMce::className(), [
            'options' => ['rows' => 6],
            'language' => 'es',
            'clientOptions' => [
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            ]
        ]);?>
    </div>
     <div class="col-sm-3 mt35">
     <?= $form->field($attachModel, 'attachment_path[]')->fileInput(['multiple' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>