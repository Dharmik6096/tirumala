<?php
$this->title = Yii::t('app', 'Vehicle Trip Map');
?>
<div class="trip-map-container">
    <h2 class="trip-title">Vehicle Trip Status</h2>
    <h4>Trip Code: <?= !empty($tripTrack[0]->trip_code) ? $tripTrack[0]->trip_code : '' ?></h4>
    <h4>Parsing No: <?= !empty($parsingNo->parsing_no) ? $parsingNo->parsing_no : '' ?></h4>
    <div class="image_sec">
        <img src="themes\pcdf\assets\images\milk_truck.jpg" alt="Food Icon" width="80" class="image">
    </div>
    <?php if (!empty($tripTrack)) { ?>
        <div class="trip-timeline">
            <?php
            foreach ($tripTrack as $index => $trip) {
                $stepClass = ($index % 2 == 0) ? 'step-up' : 'step-down';
                ?>
                <div class="step <?= $stepClass ?>">

                    <div class="step-content">
                        <h5><strong><?= ucwords(str_replace('_', ' ', $trip->trip_sub_status)) ?></strong></h5>
                        <p><?= $trip->remarks ?></p>
                        <h6><?= Yii::$app->controls->view_datetime($trip->sub_status_time) ?></h6>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="emptymsg"><b>Data Not Found.</b></div>
        <?php } ?>
    </div>
</div>
