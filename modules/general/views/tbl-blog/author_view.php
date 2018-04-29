<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblBlog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Blogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="tbl-blog-view">

    <h1><?php /* Html::encode($this->title) ?></h1>

  <p>
  <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
  <?=
  Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
  'class' => 'btn btn-danger',
  'data' => [
  'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
  'method' => 'post',
  ],
  ]) */
?>
    </p>


    <h3>Attachments</h3>


</div>-->


<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Html::encode($this->title) ?>
        <div class="dropdown pull-right">
            <button data-toggle="dropdown" class="dropdown-toggle btn btn-danger">Actions <b class="caret"></b></button>
            <ul class="dropdown-menu">
                <li><?= Yii::$app->controls->update($model->id); ?></li>
                <li><?= Yii::$app->controls->cancel($model); ?></li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?=
            DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-bordered detail-view'],
                'attributes' => [
                    //'id',
                    'title',
                    'description',
                    //'user_id',
                    'created_at',
                    'created_by',
                    'is_active',
                //'updated_at',
                //'updated_by',
                ],
            ])
            ?>

            <div class="col-sm-12 mt35"><h5 class="panel-subtitle">Attachments</h5></div>

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'attachment_path',
                //['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
        </div>
    </div>
</div>