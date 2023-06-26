<?php
$this->title = Yii::$app->label->title('create', 'Staff Member Designation');

use yii\helpers\Html;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\widgets\Pjax;
use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;
use kartik\grid\GridView;
?>

<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $allowUpdate = true;
        if (!empty($model) && $model->disableEdit()) {
            $allowUpdate = false;
        }
        ?>
        <div class = "MainForm">
            <?=
            $this->render('staff_design_from', [
                'model' => $model,
                'type' => 'create',
            ])
            ?>
        </div>
        <div class="DetailGrid">
            <?=
            $this->render('staff_design_detail_form', [
                'model' => $model,
                'type' => 'edit',
                'existData' => $existData,
                'allowUpdate' => $allowUpdate,
            ])
            ?>
        </div>
    </div>
</div>

