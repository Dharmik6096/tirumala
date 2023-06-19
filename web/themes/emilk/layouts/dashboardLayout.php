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
        <?php
        $this->head();
        $script = "var delay=350;";
        Yii::$app->view->registerJs($script, View::POS_END, 'time-loader');
        ?>

    </head>
    <body>

        <?php $this->beginBody() ?>
        <!-- Page Loader -->
        <div id="pageloader">
            <div id="loadercontent"></div>
        </div>
        <!-- / Page Loader -->
<!--   <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
     <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
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

        <div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <?= $content ?>
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