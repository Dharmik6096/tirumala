<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\general\models\TblBlog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Blogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$folder = Yii::getAlias('@webroot') . '/web/uploads/attachments/';
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
  ])
  ?>
  </p>
  <?=
  DetailView::widget([
  'model' => $model,
  'attributes' => [
  'id',
  'title',
  'description',
  'user_id',
  'created_at',
  'created_by',
  'is_active',
  'updated_at',
  'updated_by',
  ],
  ]) */
?>
</div>-->

<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="blog-container blog-detail-container">
            <div class="blog-img">
                <img class="img-responsive" src="../../themes/emilk/assets/images/no-image-available.jpg" alt=""/>
            </div>
            <div class="blog-content clearfix">
                <div class="blog-desc">
                    <h4><?= $model->title; ?></h4>
                    <h6><i class="fa fa-user-o"></i> John Doe, <em>Web Developer</em></h6>
                    <h6><i class="fa fa-calendar-o"></i> <?= $model->created_at; ?></h6>
                    <?= $model->description; ?>
                </div>
            </div>
                <div class="blog-attach clearfix">
                    <ul>
                        <?php
                        foreach ($model->tblBlogAttachments as $file) {
                            $name = explode('.', $file->attachment_path);
                            $ext = '.' . $name[1];
                            $url = Url::to(['/web/uploads/attachments/' . $file->attachment_path]);
                            echo Yii::$app->general->getAttachmentLink($ext, $url);
                            echo "<p><a href='".$url."'>" . $file->attachment_path . "</a></p>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="blog-meta">
                    <h6><i class="fa fa-paperclip"></i>3</h6>
                    <h6><i class="fa fa-eye"></i>256</h6>
                </div>
            
        </div>
    </div>
</div>

<!-- Modal -->
<!--<div id="attachedImg" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <img src="" alt="" class="img-responsive">
            </div>
        </div>
    </div>
</div>-->