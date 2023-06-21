<!--header starts-->
<?php

use yii\helpers\Url;

$logo = $this->theme->getUrl('/assets/images/logo.png');
$eipl_code = Yii::$app->session->get('eiplCode');
$logo_code = Yii::$app->session->get('organization_logo');
if (!empty($eipl_code)) {
    $logo_image = !empty($logo_code) ? $logo_code : strtolower($eipl_code) . '.png';
    $new_logo = $this->theme->getUrl('/assets/images/union_logo/') . $logo_image;
    $dir_path = Yii::$app->basePath . '/' . substr(Yii::$app->params['logo_path'], 1) . $logo_image;
    $logo = file_exists($dir_path) ? $new_logo : $logo;
}
?>

<div class="navbar fixed-top menu-wrap navbar-expand-lg navbar-lightasd bg-lightasd">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= Url::to(['/site/dashboard']) ?>"><img src="<?= $logo ?>" alt='<?= Yii::t('app', 'Company Logo') ?>' class="logo img-responsive"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <?php if (false && (Url::home() . 'site' == Yii::$app->request->url || Url::home() . 'site/index' == Yii::$app->request->url)) { ?>
                <span class="pull-right dashboard_set_icon"><a data-toggle="collapse" href="#collapse1"><i class="fa fa-cog faa-spin animated faa-slow"></i></a></span>
                    <?php } ?>
                    <?php require_once('tpl_navigation.php'); ?>
        </div>
    </div>
</div>
