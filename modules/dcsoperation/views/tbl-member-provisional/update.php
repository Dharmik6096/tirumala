<?php
$this->title = Yii::$app->label->title('edit', 'Provisional Members');

use yii\web\View;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            'type' => 'edit',
        ])
        ?>
    </div>
</div>
