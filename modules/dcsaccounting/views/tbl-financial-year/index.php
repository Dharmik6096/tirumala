<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Financial Year'));
?>
<div class="tbl-asset-group-index">
    <div class="panel panel-main">
        <div class="panel-heading">
            <?= Html::encode($this->title) ?>
            <div class="pull-right shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="false">
                <?= Yii::$app->controls->add('Financial Year'); ?>
                <?= Yii::$app->controls->import('financial-year', $this) ?>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <?=
                $this->render('_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>