<!--header starts-->
<?php

use yii\helpers\Url;

$logo = $this->theme->getUrl('/assets/images/logo.png');
$eipl_code = Yii::$app->session->get('eiplCode');
if (!empty($eipl_code)) {
    $logo_image = strtolower($eipl_code) . '.png';
    $new_logo = $this->theme->getUrl('/assets/images/union_logo/') . $logo_image;
    $dir_path = Yii::$app->basePath . '/' . substr(Yii::$app->params['logo_path'], 1) . $logo_image;
    $logo = file_exists($dir_path) ? $new_logo : $logo;
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