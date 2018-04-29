<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\web\View;

AppAsset::register($this);
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
        Yii::$app->view->registerJs($script, View::POS_HEAD, 'time-loader');
        ?>
    </head>
    <body>

        <?php $this->beginBody() ?>
        <!-- Page Loader -->
        <div id="pageloader">
            <div id="loadercontent"></div>
        </div>
        <!-- / Page Loader -->
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
        ?>
        <?php require_once('templates/tpl_header.php'); ?>

        <div class="sidebar-layout">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-9 col-md-10 col-sm-print-12">
                        <?= $content ?>
                    </div>
                    <div class="col-sm-3 col-md-2 padding-left-0 hidden-print">
                        <?php require_once('templates/tpl_sidebar.php'); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once('templates/tpl_footer.php'); ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
<script type="text/javascript">
    var SearchArray = {};
    var SearchParam = {};
    SearchArray = '<?php echo json_encode(Yii::$app->request->queryParams); ?>';
    $.each(JSON.parse(SearchArray), function (index, value) {
        if (typeof value !== 'object' && index !== 'q') {
            SearchParam[index] = value;
        }
    });
</script>