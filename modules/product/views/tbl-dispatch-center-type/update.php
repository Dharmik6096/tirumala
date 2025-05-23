<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblDispatchCenterType */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Tbl Dispatch Center Type',
        ]) . $model->dispatch_center_type_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Dispatch Center Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dispatch_center_type_code, 'url' => ['view', 'id' => $model->dispatch_center_type_code]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-dispatch-center-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
