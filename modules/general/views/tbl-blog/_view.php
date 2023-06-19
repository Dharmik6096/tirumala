<?php 
use yii\helpers\Url;
use yii\helpers\Html;
$url=  Url::to(['view','id'=>$model->id])
?>
<div class="col-sm-12">
    <div class="blog-container">
        <div class="row">
            <div class="col-sm-4">
                <div class="blog-img">
                    <img class="img-responsive" src="../themes/emilk/assets/images/no-image-available.jpg" alt=""/>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="blog-content">
                    <div class="blog-desc">
                        <h4><?= $model->title ?></h4>
                        <h6><i class="fa fa-user-o"></i> John Doe, <em>Web Developer</em></h6>
                        <h6><i class="fa fa-calendar-o"></i> 31 Mar, 2017</h6>
                        <p><?= $model->description ?></p>
                        <?= Html::a('Read More <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', $url)?>
                        
                    </div>
                    <div class="blog-meta">
                        <h6><i class="fa fa-paperclip"></i>3</h6>
                        <h6><i class="fa fa-eye"></i>256</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>