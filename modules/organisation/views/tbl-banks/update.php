<?php
$this->title = Yii::$app->label->title('edit', 'Bank');

?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'edit', 'disable' => $disable,'map_model'=>$map_model,'district'=>$district
        ])
        ?>
    </div>
</div>