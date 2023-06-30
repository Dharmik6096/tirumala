<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Head Load'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
        <div class="dropdown pull-right shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="true">
            <button data-bs-toggle="dropdown" class="dropdown-toggle btn btn-danger">Actions <b class="caret"></b></button>
            <ul class="dropdown-menu">
                <li><?= Yii::$app->controls->add('Head Load'); ?></li>
            </ul> 
        </div>
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

