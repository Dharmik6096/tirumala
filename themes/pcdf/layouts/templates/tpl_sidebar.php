<?php 
use yii\helpers\Url;
?>
<?php if(!empty($this->params['menu'])){ ?>
<div class="panel panel-button">
    <div class="panel-body">
        <ul>
            <?php
            foreach($this->params['menu'] as $menu){
                echo '<li>'.$menu.'</li>';
            }
            ?>
        </ul>
    </div>
</div>

<?php } ?>

 <?php $notifications = Yii::$app->general->getNotifications(); ?>

<div class="panel panel-default panel-grid panel-sidebar">
    <div class="panel-heading">Notifications</div>
    <div class="panel-body">
        <div class="notification-list">
            
            <?php if (!empty($notifications)) {
                foreach ($notifications as $nf) {
                    ?>
                    <div class="notification-list-item">
                        <a href="<?php echo Url::to(['/notification/tbl-notifications/view', 'id' => $nf['id']]); ?>">
                            <h5><?= $nf['title'] ?></h5>
                            <p><?= $nf['msg'] ?></p>
                        </a>
                    </div>
                <?php
                }
                } else {
                    ?>
                    <div class="notification-list-item">
                        <p>Data not available</p>
                    </div>
                <?php } ?>
        </div>
    </div>
    <div class="panel-footer">
        <a href="<?php echo Url::to(['/notification/tbl-notifications/index']); ?>">View All <i class="fa fa-long-arrow-right"></i></a>
    </div>
</div>