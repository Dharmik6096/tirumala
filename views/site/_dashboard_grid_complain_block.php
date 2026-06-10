<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

$class_cols = $class_cols;
?>

<div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>" style="text-align: center; padding: 25px 15px;">
    <p class="dash_block_header" style="text-align: center; color: #00a4e3; font-weight: bold; font-size: 15px; margin-bottom: 10px;"><?= Yii::t('app', 'Total Complain') ?></p>
    <h4 class="dash_block_value block_value" style="text-align: center; font-size: 20px; font-weight: bold; margin-top: 15px;"><?= $blockData['total_complain'] ?? 0 ?></h4>
</div>

<div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>" style="text-align: center; padding: 25px 15px;">
    <p class="dash_block_header" style="text-align: center; color: #00a4e3; font-weight: bold; font-size: 15px; margin-bottom: 10px;"><?= Yii::t('app', 'Created Complain') ?></p>
    <h4 class="dash_block_value block_value" style="text-align: center; font-size: 20px; font-weight: bold; margin-top: 15px;"><?= $blockData['created_complain'] ?? 0 ?></h4>
</div>

<div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>" style="text-align: center; padding: 25px 15px;">
    <p class="dash_block_header" style="text-align: center; color: #00a4e3; font-weight: bold; font-size: 15px; margin-bottom: 10px;"><?= Yii::t('app', 'Assigned Complain') ?></p>
    <h4 class="dash_block_value block_value" style="text-align: center; font-size: 20px; font-weight: bold; margin-top: 15px;"><?= $blockData['assign_complain'] ?? 0 ?></h4>
</div>

<div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>" style="text-align: center; padding: 25px 15px;">
    <p class="dash_block_header" style="text-align: center; color: #00a4e3; font-weight: bold; font-size: 15px; margin-bottom: 10px;"><?= Yii::t('app', 'Inprogress Complain') ?></p>
    <h4 class="dash_block_value block_value" style="text-align: center; font-size: 20px; font-weight: bold; margin-top: 15px;"><?= $blockData['inprogress_complain'] ?? 0 ?></h4>
</div>

<div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>" style="text-align: center; padding: 25px 15px;">
    <p class="dash_block_header" style="text-align: center; color: #00a4e3; font-weight: bold; font-size: 15px; margin-bottom: 10px;"><?= Yii::t('app', 'Closed Complain') ?></p>
    <h4 class="dash_block_value block_value" style="text-align: center; font-size: 20px; font-weight: bold; margin-top: 15px;"><?= $blockData['close_complain'] ?? 0 ?></h4>
</div>

<div class="div_grid_dash_block dashboardWidgetDetailPortion <?= $class_cols ?>" style="text-align: center; padding: 25px 15px;">
    <p class="dash_block_header" style="text-align: center; color: #00a4e3; font-weight: bold; font-size: 15px; margin-bottom: 10px;"><?= Yii::t('app', 'Resolved Complain') ?></p>
    <h4 class="dash_block_value block_value" style="text-align: center; font-size: 20px; font-weight: bold; margin-top: 15px;"><?= $blockData['resolved_complain'] ?? 0 ?></h4>
</div>
