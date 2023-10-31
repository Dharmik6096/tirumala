
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\dcsoperation\models\TblCanMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if ($searchModel->is_active == 1) {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Scheme Rate'));
    $this->params['menu'][] = Yii::$app->controls->add('Scheme Rate');
    $this->params['menu'][] = Yii::$app->controls->import('scheme-rate', $this);
} else {
    $this->title = Yii::t('app', Yii::$app->label->title('list', 'Deleted Scheme Rate'));
}
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body hide-grid-export">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>