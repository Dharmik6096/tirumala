<?php
$this->title = Yii::$app->label->title('edit', 'Provisional Members').' > '.$model->application_no;

use yii\web\View;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">

        <?php
        echo $this->render('_form', [
            'model' => $model,
            'type' => 'edit',
        ]);
        ?>
    </div>
</div>
