<?php

use app\modules\usermanagement\models\User;
use yii\helpers\Html;
use app\components\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\modules\usermanagement\models\User $model
 * @var yii\bootstrap\ActiveForm $form
 */
$title = Yii::$app->label->title($type, 'user');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);
$readOnly = false;
$isNewRecord = (isset($type) && $type == 'create') ? TRUE : FALSE;
$classs = (isset($type) && $type == 'edit') ? 'mt18' : '';

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
//           echo $form->field($model->loadDefaultValues(), 'status', [ 'options' => ['class' => 'form-group col-sm-2',]])
//                ->dropDownList(User::getStatusList())
    ?>

    <?php // $form->field($model, 'user_code', [ 'options' => ['class' => 'form-group col-sm-2',]])->textInput(['maxlength' => 255, 'autocomplete' => 'off', 'readOnly' => $readOnly]) ?>

    <div class="col-sm-2">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'username')->textInput(['maxlength' => 255, 'autocomplete' => 'off', 'readOnly' => $readOnly]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    </div>

    <div class="col-sm-2">
        <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
    </div>
    <?php if ($isNewRecord): ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off', 'class' => 'form-control check_password_strength']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
        </div>

    <?php endif; ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('dcs_union_login_type', $model, $form, '', 'Login Type', false, 'login_type'); ?>
    </div>
    <div class="col-sm-2 mt18 user_type_show">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'allow_app_login'); ?>
    </div> 
    <div class="col-sm-2 user_type_show">
        <?= Yii::$app->dropdown->dropdown('department', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('department'), false, 'department'); ?>
    </div>

    <div class="col-sm-2 h450">
        <?= Yii::$app->dropdown->dropdown('designation_code', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('designation_code'), false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('user', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('primary_parent'), false, 'primary_parent'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('user', $model, $form, 'col-sm-3 form-group', $model->getAttributeLabel('secondary_parent'), false, 'secondary_parent'); ?>
    </div>
    <?php
    $union_code = explode(',', Yii::$app->session->get('Unions'));
    $inventory_with_dispatch_center = Yii::$app->general->getUnionConfiguration($union_code[0], 'inventory_with_dispatch_center', 'PORTAL') == 1 ? true : false;
    if ($inventory_with_dispatch_center) {
        ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('dispatch_center_type', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', $model->getAttributeLabel('dispatch_center_type'), false, 'dispatch_center_type_code'); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dispatchCenterType($model, $form, 'user-dispatch_center_type_code', 'dispatch_center_code', $model->getAttributeLabel('dispatch_center'), TRUE); ?>
        </div>
    <?php }
    ?>
    <div class="col-sm-2 mt18">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form, 'is_engineer'); ?>
    </div>
    <?php /* if ($model->checkNotSelf()) { ?>
      <div class="col-sm-2 mt25">
      <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
      </div>
      <?php } */ ?>

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
        <div class="clearfix"></div>
        <div class="col-sm-2 multiple">
            <?php
            echo $form->field($model, 'role')
                    ->dropDownList(\app\modules\usermanagement\models\User::getAvailableRoles(), ['multiple' => 'multiple']);
            ?>
        </div>
    <?php endif; ?>
    <div class="col-sm-2">
        <?= $form->field($model, 'employee_id')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'date_of_joining'); ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php BootstrapSwitch::widget() ?>
