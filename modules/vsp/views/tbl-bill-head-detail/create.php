<?php
$this->title = Yii::$app->label->title('create', 'Vendor Bill Head Transaction');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <div id="maincontent">
            <?=
            $this->render('_form', [
                'model' => $model,
                'type' => 'create',
            ])
            ?>  
        </div>
        <div id="gridcontentSet" class='hide-grid-settings panel_clear_both'>
            <?=
            $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider])
            ?>
        </div>
    </div>
</div>