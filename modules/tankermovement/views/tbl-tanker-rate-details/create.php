<?php

use yii\helpers\Html;

$this->title = Yii::$app->label->title('create', 'Tanker Rate');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= $this->title ?>
    </div>
    <div class="panel-body">
        <?php
        if (isset($method) && $method == 1)
            echo $this->render('_manual_section', ['purchaseBasedModel' => $purchaseBasedModel]);
        else
            echo $this->render('_auto_section', ['purchaseBasedModel' => $purchaseBasedModel,'jsonEncoded' => $jsonEncoded,'quality_param'=>$quality_param]);
        ?>
    </div>
</div>
