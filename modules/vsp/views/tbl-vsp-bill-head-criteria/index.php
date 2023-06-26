<?php

use app\modules\usermanagement\components\GhostHtml;
use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Bill Head Criteria'));
$this->params['menu'][] = Yii::$app->controls->add('Bill Head Criteria');
$url_path = [];
$url_path[] = 'online-collection';
$url = Url::to(array_values($url_path));
?>
<div class="tbl-banks-index">
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
