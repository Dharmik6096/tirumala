<?php

use yii\helpers\Html;

$this->title = Yii::$app->label->title('create', 'Purchase Rate Range');
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= $this->title ?>
        <div class="pull-right">
            <?php //if ($purchaseBasedModel[0]->purchase_rate_code!=-1) echo Html::a('View Chart', ['rate-chart', 'id' => $purchaseBasedModel[0]->purchase_rate_code, 'milk_type' => 1], ['class' => 'btn btn-danger apply-shortcut']); ?>
        </div>
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