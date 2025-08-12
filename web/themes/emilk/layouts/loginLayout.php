<?php

use app\assets\LoginAsset;
use yii\bootstrap5\BootstrapAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $content string */

$this->title = yii::t('app', 'Everest');
LoginAsset::register($this);
BootstrapAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>

        <?php $this->beginBody() ?>

        <?= $content ?>

        <?php $this->endBody() ?>

    </body>
</html>
<?php $this->endPage() ?>
<script type="text/javascript">
    $('.login-submit').on('click', function () {
        var login_enc_key = '<?= Yii::$app->session->get('login_enc_key'); ?>';
        var login_username = $('#loginform-login_username').val();
        var login_password = $('#loginform-login_password').val();
        if (login_username != '' && login_password != '') {
            var KeyObj = CryptoJS.enc.Utf8.parse(login_enc_key);
            username = CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(login_username), KeyObj, {mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.ZeroPadding});
            password = CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(login_password), KeyObj, {mode: CryptoJS.mode.ECB, padding: CryptoJS.pad.ZeroPadding});
            $('#loginform-username').val(username);
            $('#loginform-password').val(password);
        }
        $('#login-form').submit();
    });
</script>