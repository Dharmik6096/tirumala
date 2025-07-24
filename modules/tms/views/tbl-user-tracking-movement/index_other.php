<?php

use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Individual Location of a User'));
$latLongArray = [];
$infoArray = [];
if (!empty($onlineData)) {
    foreach ($onlineData as $data) {
        $datetime = $data['tracking_datetime'];
        $date = date('Y-m-d', strtotime($datetime));
        $time = date('H:i', strtotime($datetime));
        if (!empty($data['lat']) && !empty($data['long'])) {
            $info = '<div class="map_info_content">';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Date') . ':</span> ' . $date . '</p>';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Time') . ':</span> ' . $time . '</p>';
            $info .= '</div>';

            $latLongArray[] = [
                'info' => $info,
                'lat' => $data['lat'],
                'long' => $data['long'],
            ];
        }
    }
}

if (empty($latLongArray)) {
    $latLongArray[] = [
        'info' => 'Default Location',
        'lat' => 23.051489132364928,
        'long' => 72.49427197180005,
    ];
}

$latLongArray = json_encode($latLongArray);
$mapIcon = $this->theme->getUrl('/assets/images/marker-icon.png');
$googleMapKey = Yii::$app->params['google_map_api_key'];
?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= Html::encode($googleMapKey) ?>&libraries=&v=weekly" defer></script>
<div class="panel panel-default panel-grid tbl-complain-view hide-grid-settings">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <div class="grid-search large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'allUser' => FALSE]); ?>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="panel-heading">
                <?= Yii::t('app', 'Location of Selected User'); ?>
            </div>
            <div class="col-sm-12">
                <div id="map" style="height: 500px;"></div> <!-- Set a height for the map -->
            </div>
        </div>
    </div>
</div>

<?php
$script = <<<JS
    var locations = $latLongArray;
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: new google.maps.LatLng(locations[0].lat, locations[0].long),
    });
    var infoWindow = new google.maps.InfoWindow();
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
    });

    if (locations.length > 1) {
        var waypoints = locations.slice(1, -1).map(function(location) {
            return {
                location: new google.maps.LatLng(location.lat, location.long),
                stopover: true,
            };
        });

        var request = {
            origin: new google.maps.LatLng(locations[0].lat, locations[0].long),
            destination: new google.maps.LatLng(locations[locations.length - 1].lat, locations[locations.length - 1].long), // End point
            waypoints: waypoints,
            travelMode: google.maps.TravelMode.DRIVING,
        };

        directionsService.route(request, function(result, status) {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
            } else {
                console.error('Directions request failed due to ' + status);
            }
        });
    } else {
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[0].lat, locations[0].long),
            map: map,
            icon: "$mapIcon",
        });

        marker.addListener('click', function() {
            infoWindow.setContent(locations[0].info);
            infoWindow.open(map, marker);
        });
    }
JS;

$this->registerJs($script, View::POS_READY, 'user-tracking-movement');
?>
