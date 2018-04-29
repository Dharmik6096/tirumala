<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\installation\models\InstallationIdentitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Installation Identity'));
?>
<div class="installation-identity-index">

    <div class="panel panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
            <div class="pull-right shortcut-main" shortcut="true" display_shortcut="false" highlight_shortcut="true">
                <?= Yii::$app->controls->add('Installation Identity'); ?>
                
            </div>
        </div>

        <div class="panel-body">
            <div class="table-responsive"><?=
                $this->render('_form_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ])
                ?>
            </div>
        </div>
    </div>
</div>