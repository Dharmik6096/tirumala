<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\web\View;
use app\assets\GuestAssets;
use yii\helpers\Url;
use app\modules\organisation\models\TblUnions;

GuestAssets::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php
        $this->head();
        $script = "var delay=350;";
        Yii::$app->view->registerJs($script, View::POS_END, 'time-loader');
        ?>

    </head>
    <body>

        <?php $this->beginBody() ?>
        <?php
        if (Yii::$app->session->hasFlash('success')) {
            $msg = Yii::$app->session->getFlash('success');
            if (isset($msg['type'])) {
                $type = isset($msg['type']) ? $msg['type'] : 'success';
                $field = isset($msg['field']) ? $msg['field'] : '';
                $hiddenfield = isset($msg['hidden_field']) ? $msg['hidden_field'] : '';
                $alertType = $type == 'success' ? 'successbar' : 'errorbar';
                Yii::$app->display->show($msg['message'], $alertType, $type, $field, $hiddenfield);
            }
        }
        $unionData = TblUnions::find()->where(['is_active' => 1])->one();
        $eiplCode = !empty($unionData) && !empty($unionData->eipl_code) ? ($unionData->eipl_code) : '';
        $logo = $this->theme->getUrl('/assets/images/logo.png');
        if ($eiplCode == 'CARGILL') {
            $logo = $this->theme->getUrl('/assets/cargill/images/logo.png');
        } else if ($eiplCode == 'KOTMALE') {
            $logo = $this->theme->getUrl('/assets/kotmale/images/logo.png');
        } else if ($eiplCode == 'THIRUMALA') {
            $logo = $this->theme->getUrl('/assets/images/nav_logo.png');
        } else if ($eiplCode == 'PRABHAT') {
            $logo = $this->theme->getUrl('/assets/images/nav_logo.png');
        } else if ($eiplCode == 'ANIK') {
            $logo = $this->theme->getUrl('/assets/images/nav_logo.png');
        } else if ($eiplCode == 'BANAS') {
            $logo = $this->theme->getUrl('/assets/images/banas_logo.png');
        }
        ?>

        <div class="navbar fixed-top menu-wrap navbar-expand-lg navbar-lightasd bg-lightasd">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= Url::to(['/site/dashboard']) ?>"><img src="<?= $logo ?>" alt='<?= Yii::t('app', 'Company Logo') ?>' class="logo img-responsive"/></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>

        <div class="sidebar-layout">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-sm-print-12">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>