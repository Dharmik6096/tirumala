<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Yii::$app->label->title('edit', 'Tanker Milk Lot Quality') . ' > ' . $model->trip_code;
Url::remember();
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?=
        $this->render('_form_update', [
            'model' => $model,
            'config_list' => $config_list,
            'config' => $config,
            'configTxnData' => $configTxnData,
            'type' => 'edit'
        ])
        ?>
    </div>
</div>
