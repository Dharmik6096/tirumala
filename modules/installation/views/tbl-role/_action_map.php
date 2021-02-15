<?php
$this->title = Yii::t('app', 'Role Action Mapping'. ': ' .$models->description) ;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <?php
        echo $this->render('_role_map_grid', ['model' => $model, 'selectedArray' => $selectedArray, 'model' => $model, 'menuArray' => $menuArray, 'mappingModel' => $mappingModel, 'id' => $id]);
        ?>
    </div>
</div>
