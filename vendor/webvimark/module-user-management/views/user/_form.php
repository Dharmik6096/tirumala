<?php

use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 * @var yii\bootstrap\ActiveForm $form
 */
$title = Yii::$app->label->title($type, 'user');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);
$readOnly = false;
$isNewRecord = (isset($type) && $type == 'create') ? TRUE : FALSE;

if (!$isNewRecord)
    $readOnly = true;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'user',
            'validateOnBlur' => false,
        ]);
?>

<?php echo $form->errorSummary($model); ?>
<div class="row">
    <?php
//           echo $form->field($model->loadDefaultValues(), 'status', [ 'options' => ['class' => 'form-group col-sm-3',]])
//                ->dropDownList(User::getStatusList())
    ?>

    <?php // $form->field($model, 'user_code', [ 'options' => ['class' => 'form-group col-sm-3',]])->textInput(['maxlength' => 255, 'autocomplete' => 'off', 'readOnly' => $readOnly]) ?>

    <div class="col-sm-3">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'username')->textInput(['maxlength' => 255, 'autocomplete' => 'off', 'readOnly' => $readOnly]) ?>
    </div>
    <?php if (User::hasPermission('editUserEmail')): ?>
        <div class="col-sm-3">
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
        </div>
    <?php endif; ?>
    <div class="col-sm-3">
        <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => 255, 'autocomplete' => 'off', 'readOnly' => $readOnly]) ?>
    </div>
    <?php if ($isNewRecord): ?>
        <div class="col-sm-3">
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
        </div>
        <div class="col-sm-3 mt25 user_type_show">
            <?= $form->field($model, 'allow_app_login', ['checkboxTemplate' => "<div class='checkbox mb0'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}"])->checkbox(['uncheck' => 0, 'value' => 1]); ?>
        </div> 
        <div class="col-sm-3 user_type_show">
            <?= Yii::$app->dropdown->dropdown('department', $model, $form, '', $model->getAttributeLabel('department'), false, 'department'); ?>
        </div>
    <?php endif; ?>

    <?php /* if ($model->checkNotSelf()) { ?>
      <div class="col-sm-3 mt25">
      <?= Yii::$app->controls->active($model, $form); ?>
      </div>
      <?php } */ ?>
    <div class="clearfix"></div>

    <?php if ($isNewRecord): ?>
        <?php
//                echo $form->field($model, 'organizations', ['options' => ['class' => 'form-group col-sm-6']])
//                        ->widget(DepDrop::classname(), [
//                            'options' => ['multiple' => true],
//                            'pluginOptions' => [
//                                'depends' => ['user-user_type_id'],
//                                'placeholder' => FALSE,
//                                'url' => Url::to(['/user-management/user/get-orgazinations']),
//                                'initialize' => true
//                            ]
//                        ])->label(true);
        ?>
        <?php
        /* echo $form->field($model, 'role', ['options' => ['class' => 'form-group col-sm-6']])
          ->widget(DepDrop::classname(), [
          'options' => ['multiple' => true],
          'pluginOptions' => [
          'depends' => ['user-portal_type'],
          'placeholder' => FALSE,
          'url' => Url::to(['/user-management/user/get-roles']),
          'initialize' => true
          ]
          ])->label(true); */
        ?>
        <div class="col-sm-6">
            <?php
            echo $form->field($model, 'role')
                    ->dropDownList(User::getAvailableRoles(), ['multiple' => 'multiple']);
            ?>
        </div>
    <?php endif; ?>

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
<?php BootstrapSwitch::widget() ?>
