<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'E-mail confirmed');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change-own-password-success">

    <div class="alert alert-success text-center">
        <?= Yii::t('app', 'E-mail confirmed') ?> - <b><?= $user->email ?></b>

        <?php if (isset($_GET['returnUrl'])): ?>
            <br/>
            <br/>
            <b><?= Html::a(Yii::t('app', 'Continue'), $_GET['returnUrl']) ?></b>
        <?php endif; ?>
    </div>

</div>
