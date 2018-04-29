<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsaccounting\models\TblUnionBillHead */

$this->title = Yii::t('app', Yii::$app->label->title('create', 'Head Load Transaction'));
?>
<div class="tbl-head-load-transaction-create">
    <div class="panel panel-main">
        <div class="panel-body">
            <div class="panel-subheading">
                <?=
                $this->render('_form', [
                    'model' => $model, 'type' => 'create',
                ])
                ?>
                <div class="panel-table-subtitle">Head Load Transaction List</div>
            </div>

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
