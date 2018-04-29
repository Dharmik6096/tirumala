<?php
$this->title = Yii::$app->label->title('edit', 'Election Detail');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model, 'type' => 'edit'
        ])
        ?>
        <div class="row">
            <div class="form-grid">
                <?=
                $this->render('_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>