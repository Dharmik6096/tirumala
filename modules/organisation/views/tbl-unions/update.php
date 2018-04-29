<?php
$this->title = Yii::$app->label->title('edit', 'Union');

use yii\web\View;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit'
        ])
        ?>
    </div>
</div>
<?php
$script = "
        $(document).ajaxStop(function() {
            $('.depend-control').each(function(){
                $(this).attr('disabled', 'disabled');
            })
        });";
Yii::$app->view->registerJs($script, View::POS_READY,'disable-dep');
?>