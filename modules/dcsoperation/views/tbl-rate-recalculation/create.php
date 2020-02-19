<?php
$this->title = Yii::$app->label->title('create', 'Rate Recalculation BMC');
?>
<div class="panel panel-default panel-grid panel-main hide_grid_search_filter hide_grid_settings_filter">
    <div class="panel-heading">
        <?= $this->title ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => 'create',
            'rtype'=>$rtype
        ])
        ?>        
    </div>
</div>