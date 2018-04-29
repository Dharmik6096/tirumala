<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\web\View;
use app\assets\DashboardAssets;

DashboardAssets::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head();
            $script = "var delay=350;";
        Yii::$app->view->registerJs($script, View::POS_END, 'time-loader');?>


</head>
<body>

<?php $this->beginBody() ?>

    <!--content area starts-->
        <section class="main-content installation-content">
            <div class="container-fluid">
               <div class="row">

                <div class="content-section">
                    <?= $content ?>
                </div>
               </div>
            </div>
        </section>
    <!--content area starts-->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
