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
    <div class="trip-timeline-wrapper">
        <button class="scroll-button left-arrow" onclick="scrollTimeline(-200)">
            &#10094&#10094;
        </button>
        <div class="trip-timeline" id="tripTimeline">
            <?php if (!empty($tripTrack)) { ?>
                <?php
                foreach ($tripTrack as $index => $trip) {
                    $stepClass = ($index % 2 == 0) ? 'step-up' : 'step-down';
                    $remarks = explode('-', $trip->remarks);
                    ?>
                    <div class="step <?= $stepClass ?>">

                        <div class="step-content">
                            <?php
                            $labelMap = [
                                'cleaning_pending' => 'Plant Lot Done',
                                'qa_pending' => 'Cleaning Done',
                                'tanker_qualified' => 'QA Done',
                            ];
                            $customLabel = isset($labelMap[$trip->trip_sub_status]) ? $labelMap[$trip->trip_sub_status] : ucwords(str_replace('_', ' ', $trip->trip_sub_status));
                            ?>
                            <h5><strong><?= $customLabel ?></strong></h5>
                            <p><?= isset($remarks[0]) ? $remarks[0] : '' ?> - <?= isset($remarks[1]) ? $remarks[1] : '' ?></p>
                            <h6><?= Yii::$app->controls->view_datetime($trip->sub_status_time) ?></h6>
                            <p class="mt10"><?= isset($remarks[2]) ? $remarks[2] : '' ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="emptymsg"><b>Data Not Found.</b></div>
            <?php } ?>
        </div>
        <button class="scroll-button right-arrow" onclick="scrollTimeline(200)">
            &#10095&#10095;
        </button>
    </div>
</div>

<script>
    function scrollTimeline(scrollAmount) {
        const timeline = document.getElementById('tripTimeline');
        timeline.scrollLeft += scrollAmount;
    }
</script>
