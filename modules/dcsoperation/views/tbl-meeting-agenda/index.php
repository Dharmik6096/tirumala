<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\dcsoperation\models\TblMeetingAgendaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::$app->label->title('list', 'meeting agenda'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
<?= $this->title; ?>
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

