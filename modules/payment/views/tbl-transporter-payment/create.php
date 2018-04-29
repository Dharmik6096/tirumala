<?php
$this->title = 'Transporter Payment : Step 1';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form', [
            'model' => $model,
            'type' => 'create',
            'dataProvider' => $dataProvider,
        ])
        ?>
    </div>
</div>
