<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\general\models\TblBloodgroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Bloodgroups');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?>
        <div class="dropdown pull-right shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="true">
            <button data-toggle="dropdown" class="dropdown-toggle btn btn-danger">Actions <b class="caret"></b></button>
            <ul class="dropdown-menu">
                <li><?= Yii::$app->controls->add('Bloodgroup'); ?></li>
            </ul>
        </div>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
<!--    <p>
    <?php // Html::a(Yii::t('app', 'Create Tbl Bloodgroup'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <div class="panel-body">
        <div class="table-responsive">
            <?php Pjax::begin(); ?>    <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'blood_group_code',
                    'blood_group',
                    'is_active',
                    'is_delete',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>