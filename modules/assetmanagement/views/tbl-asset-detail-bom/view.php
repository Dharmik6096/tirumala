<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\assetmanagement\models\TblAssetDetailBom */

$this->title = $model->asset_detail_bom_code;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Asset Detail Boms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-asset-detail-bom-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->asset_detail_bom_code], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->asset_detail_bom_code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'asset_detail_bom_code',
            'asset_detail_code',
            'spare_code',
            'serial_number',
            'qty',
            'union_code',
            'is_active',
        ],
    ])
    ?>

</div>
