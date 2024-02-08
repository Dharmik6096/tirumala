<?php


/**
 * @var yii\web\View $this
 * @var app\modules\usermanagement\models\User $user
 */

$this->title = yii::t('app', 'Registration - confirm your e-mail');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-wait-for-confirmation">

	<div class="alert alert-info text-center">
		<?= yii::t('app', 'Check your e-mail {email} for instructions to activate account', [
			'email'=>'<b>'. $user->email .'</b>'
		]) ?>
	</div>

</div>
