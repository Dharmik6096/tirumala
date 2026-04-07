<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblDispatchCenterType */

$this->title = $model->dispatch_center_type_code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Dispatch Center Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-dispatch-center-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->dispatch_center_type_code], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->dispatch_center_type_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dispatch_center_type_code',
            'dispatch_center_type',
            'is_active',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
            'x_col1',
            'x_col2',
            'x_col3',
            'x_col4',
            'x_col5',
        ],
    ])
    ?>

</div>
