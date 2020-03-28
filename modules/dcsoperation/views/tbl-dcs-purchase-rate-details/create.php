<?php

use yii\helpers\Html;
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading">
        <?= Yii::t('app', 'Milk Rate (BMC)') ?>
        <div class="pull-right">
            <?= Yii::$app->controls->cancel('', ['tbl-dcs-purchase-rate/index']); ?>
            <?php //if ($purchaseBasedModel[0]->purchase_rate_code!=-1) echo Html::a('View Chart', ['rate-chart', 'id' => $purchaseBasedModel[0]->purchase_rate_code, 'milk_type' => 1], ['class' => 'btn btn-danger apply-shortcut']); ?>
        </div>
    </div>
    <div class="panel-body">
        <?php
        if (isset($method) && $method == 1)
            echo $this->render('_manual_section', ['purchaseBasedModel' => $purchaseBasedModel[0], 'jsonEncoded' => $jsonEncoded, 'quality_param' => $quality_param]);
        else if (isset($method) && $method == 2)
            echo $this->render('_auto_section', ['purchaseBasedModel' => $purchaseBasedModel, 'jsonEncoded' => $jsonEncoded, 'quality_param' => $quality_param]);
        else
            echo $this->render('_only_formula_section', ['purchaseBasedModel' => $purchaseBasedModel, 'jsonEncoded' => $jsonEncoded, 'quality_param' => $quality_param]);
        ?>
    </div>
</div>