<?php

/**
 * @var yii\widgets\ActiveForm $form
 * @var webvimark\modules\UserManagement\models\rbacDB\Permission $model
 */
use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use app\components\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$title = Yii::$app->label->title($type, 'Permission');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'role-form',
            'validateOnBlur' => false,
        ])
?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('organizations_type', $model, $form, '', $model->getAttributeLabel('organizations_type'), false, 'organizations_type', false); ?>  
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'description')->textInput(['maxlength' => 255, 'autofocus' => $model->isNewRecord ? true : false]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 64, 'readonly' => $readonly]) ?>
    </div>
    <div class="col-sm-2">
        <?=
                $form->field($model, 'group_code')
                ->dropDownList(ArrayHelper::map(AuthItemGroup::find()->asArray()->all(), 'code', 'name'), ['prompt' => 'Select Group'])
        ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>