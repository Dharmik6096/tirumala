<?php
$this->title = Yii::$app->label->title('create', 'Product Requisition Transaction');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'jsonEncoded' => $jsonEncoded
        ])
        ?>

        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
        <?=
        $this->render('_form_submit', [
            'model' => $model, 'type' => 'create', 'jsonEncoded' => $jsonEncoded
        ])
        ?>
    </div>
</div>
