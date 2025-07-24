<?php

use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Current Location of All Users'));
$latLongArray = [];
$infoArray = [];

if (!empty($onlineData)) {
    foreach ($onlineData as $data) {
        $datetime = $data['tracking_datetime'];
        $date = date('Y-m-d', strtotime($datetime));
        $time = date('H:i', strtotime($datetime));
        if (!empty($data['lat']) && !empty($data['long'])) {
            $info = '<div class="map_info_content">';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'User Code') . ':</span> ' . $data['user_code'] . '</p>';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'User Name') . ':</span> ' . $data['user_name'] . '</p>';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Date') . ':</span> ' . $date . '</p>';
            $info .= '<p class="map_marker_content"><span class="marker_header">' . Yii::t('app', 'Time') . ':</span> ' . $time . '</p>';
            $info .= '</div>';

            $latLongArray[] = [
                'info' => $info,
                'lat' => $data['lat'],
                'long' => $data['long'],
                'serial_number' => $data['serial_number'],
            ];
        }
    }
}

if (empty($latLongArray)) {
    $latLongArray[] = [
        'info' => 'Default Location',
        'lat' => 23.051489132364928,
        'long' => 72.49427197180005,
        'serial_number' => '0'
    ];
}

$latLongArray = json_encode($latLongArray);
$mapIcon = $this->theme->getUrl('/assets/images/marker-icon.png');
$completeMapIcon = $this->theme->getUrl('/assets/images/complete-marker-icon.png');
$googleMapKey = Yii::$app->params['google_map_api_key'];
?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= Html::encode($googleMapKey) ?>&libraries=&v=weekly" defer></script>
<div class="panel panel-default panel-grid panel-main tbl-complain-view hide-grid-settings">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <div class="large-search hidden-print">
            <?php echo $this->render('_search', ['model' => $searchModel, 'dataProvider' => $dataProvider, 'allUser' => TRUE]); ?>
        </div>
        <div class="clearfix"></div>
        <?php echo $this->render('_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        <div class="row">
            <div class="panel-heading">
                <?= Yii::t('app', 'Current Location of Above Users'); ?>
            </div>
            <div class="col-sm-12">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<<JS
    var locations = $latLongArray;
    var bounds = new google.maps.LatLngBounds();
    var map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(locations[0].lat, locations[0].long),
        zoom: 15
    });
    var infoWindow = new google.maps.InfoWindow();
    
    function createCenteredNumberMarker(location) {
        var canvas = document.createElement('canvas');
        canvas.width = 25; 
        canvas.height = 41;
        
        var ctx = canvas.getContext('2d');
        var img = new Image();
        
        return new Promise(function(resolve) {
            img.onload = function() {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                var centerX = canvas.width / 2;
                var centerY = 15;
                ctx.fillStyle = 'white';
                ctx.beginPath();
                ctx.arc(centerX, centerY, 8, 0, 2 * Math.PI);
                ctx.fill();
                ctx.fillStyle = '#000000';
                ctx.font = 'bold 12px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                
                var number = location.serial_number.toString();
                if (number.length > 1) {
                    ctx.font = 'bold 10px Arial';
                }
                
                ctx.fillText(number, centerX, centerY);
                
                resolve({
                    url: canvas.toDataURL(),
                    size: new google.maps.Size(25, 41),
                    scaledSize: new google.maps.Size(25, 41),
                    anchor: new google.maps.Point(12.5, 41)
                });
            };
            
            img.src = "$mapIcon";
            
            if (img.complete) {
                img.onload();
            }
        });
    }
    
    var markerPromises = locations.map(function(location) {
        return createCenteredNumberMarker(location).then(function(icon) {
            var position = new google.maps.LatLng(
                parseFloat(location.lat), 
                parseFloat(location.long)
            );
            
            var marker = new google.maps.Marker({
                position: position,
                map: map,
                icon: icon,
                title: location.user_name
            });
            
            bounds.extend(position);
            
            marker.addListener('click', function() {
                infoWindow.setContent(location.info);
                infoWindow.open(map, marker);
            });
            
            return marker;
        });
    });
    
    Promise.all(markerPromises).then(function() {
        if (locations.length > 1) {
            map.fitBounds(bounds);
        }
    });
JS;

$this->registerJs($script, View::POS_READY, 'user-tracking-movement');
?>