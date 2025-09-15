<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\veterinary\models\TblMemberAnimalTagDetails */

$this->title = $model->member_animal_tag_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Member Animal Tag Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-member-animal-tag-details-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->member_animal_tag_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->member_animal_tag_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'member_animal_tag_id',
            'dcs_code',
            'member_code',
            'mobile_no',
            'email:email',
            'tag_no',
            'animal_type_id',
            'gender_id',
            'breed_id',
            'year',
            'month',
            'no_of_calving',
            'last_date_of_calving',
            'pregnancy_status',
            'pregnancy_month',
            'pregnancy_month_on_date',
            'milking_status',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'originating_org_code',
            'originating_org_type',
            'originating_type',
        ],
    ]) ?>

</div>
