<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['menu'][] = Yii::$app->controls->update($model->code);
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= $this->title ?>
    </div>
    <div class="panel-body">
        <?=
        DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-bordered detail-view'],
            'attributes' => [
                'name',
                'code',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ])
        ?>
    </div>
</div>