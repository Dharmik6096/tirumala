<?php
$this->title = Yii::t('app', 'Vehicle Trip Map');
?>
<div class="row">
    <div class="col-sm-4">
     </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center mt35">
                    <h3 class="">Trip Status</h3>
                    <h5 class="mt15">Trip Code : <?= !empty($tripTrack[0]->trip_code) ? $tripTrack[0]->trip_code : '' ?></h5>
                    <div class="image_sec">
                        <img src="themes\pcdf\assets\images\milk_truck.jpg" alt="Food Icon" width="80" class="image">
                    </div>
                </div>
                <?php if (!empty($tripTrack)) { ?>
                    <div class="order-status">
                        <?php
                        foreach ($tripTrack as $trip) {
                            ?>
                            <div class="status-item">
                                <i class="fa fa-circle"></i>
                                <div class="mt-5">
                                    <h5><strong><?= ucwords(str_replace('_', ' ', $trip->trip_sub_status)) ?></strong></h5>
                                    <h6 class="mt-5"><?= Yii::$app->controls->view_datetime($trip->sub_status_time) ?></h6>
                                    <p class="line-break"><?= $trip->remarks ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="emptymsg"><b>Data Not Found.</b></div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
