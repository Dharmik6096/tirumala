<?php
$this->title = Yii::$app->label->title('edit', 'Vehicle KM Information');

use yii\web\View;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_update_form', [
            'model' => $model,
            'type' => 'edit',
        ])
        ?>
    </div>
</div>