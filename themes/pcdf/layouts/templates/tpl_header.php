<!--header starts-->
<?php

use yii\helpers\Url;

$logo = $this->theme->getUrl('/assets/images/logo.png');
if (Yii::$app->session->get('organization_logo') != '') {
    $new_logo = '/' . substr(Yii::$app->params['logo_path'], 1) . Yii::$app->session->get('organization_logo');
    $logo = file_exists(Yii::$app->basePath . $new_logo) ? $new_logo : $logo;
}
?>

<div class="navbar navbar-fixed-top menu-wrap">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= Url::to(['/site/dashboard']) ?>"><img src="<?= $logo ?>" alt='<?= Yii::t('app', 'Company Logo') ?>' class="logo img-responsive"/></a>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse">
            <?php require_once('tpl_navigation.php'); ?>
        </div>
    </div>
</div>