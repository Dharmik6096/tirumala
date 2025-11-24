<?php

use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Department'));
$this->params['menu'][] = Yii::$app->controls->add('Department');
$this->params['menu'][] = GhostHtml::a('<i class="fa fa-exchange-alt"></i>' . Yii::t('app', 'Change Department Sequence'), ['/general/tbl-department/change-department-seq'], ['class' => 'btn btn-danger btn-block']);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>
