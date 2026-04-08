<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Sub Project'));
$this->params['menu'][] = Yii::$app->controls->add('Sub Project');
$this->params['menu'][] = Yii::$app->controls->import('sub-project-applicability', $this, Yii::t('app', 'Applicability Import'));
?>
<div class="tbl-branch-index">
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
</div>