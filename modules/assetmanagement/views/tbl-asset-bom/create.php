<?php $this->title = Yii::$app->label->title('create', 'Asset Bom'); ?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

        <div class="row">
            <div class="panel panel-default panel-grid panel-main">
                <div class="panel-body">
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
</div>

