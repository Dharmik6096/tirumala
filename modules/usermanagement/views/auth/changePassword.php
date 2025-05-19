<?php

use webvimark\modules\UserManagement\UserManagementModule;
use app\components\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm $model
 */
$this->title = UserManagementModule::t('back', 'Change own password');
$this->params['breadcrumbs'][] = $this->title;
$logout_url = '/user-management/auth/logout';
if (Yii::$app->session->get('Login-sess') === 'Rail') {
    $logout_url = '/site/rail-logout';
}
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'user',
                    'validateOnBlur' => false,
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-2">
                <?= $form->field($model, 'current_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off', 'class' => 'form-control check_password_strength']) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
            </div>
            <div class="col-sm-2 padding_top_20">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
if (isset($passwordChanged) && $passwordChanged):
    $loginUrl = Url::to(['/user-management/auth/login']);
    $message = "<div class='row'><div class='col-sm-12'><div class='bg-info'><i class='fa fa-info'></i></div><span>Password changed successfully.</span></div></div>";
    $escapedMessage = str_replace("'", "\\'", $message);
    $this->registerJs("
        setTimeout(function() {
            bootbox.alert({
                message: '{$escapedMessage}',
                callback: function () {
                    window.location.href = '{$loginUrl}';
                }
            });
        }, 500);
    ");
endif;
?>
