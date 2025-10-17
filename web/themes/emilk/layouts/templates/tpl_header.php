<!--header starts-->
<?php

use yii\helpers\Url;

$logo = $this->theme->getUrl('/assets/images/logo.png');
$eipl_code = Yii::$app->session->get('eiplCode');
$logo_code = Yii::$app->session->get('organization_logo');
//$logo_image = !empty($logo_code) ? $logo_code : strtolower($eipl_code) . '.png';
$logo_image = !empty($logo_code) ? $logo_code : strtolower((string)(isset($eipl_code) ? $eipl_code : '')) . '.png';
$new_logo = $this->theme->getUrl('/assets/images/union_logo/') . $logo_image;
$dir_path = Yii::getAlias('@webroot') . '/' . substr(Yii::$app->params['logo_path'], 1) . $logo_image;
$logo = file_exists($dir_path) ? $new_logo : $logo;
$portalNotification = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'portal_notification', 'PORTAL');
$portalNotificationSetInterval = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'portal_notification_set_interval', 'PORTAL');
?>

<div class="navbar fixed-top menu-wrap navbar-expand-lg navbar-lightasd bg-lightasd">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= Url::to(['/site/dashboard']) ?>"><img src="<?= $logo ?>" alt='<?= Yii::t('app', 'Company Logo') ?>' class="logo img-responsive"/></a>
        <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <?php if (false && (Url::home() . 'site' == Yii::$app->request->url || Url::home() . 'site/index' == Yii::$app->request->url)) { ?>
                <span class="pull-right dashboard_set_icon"><a data-toggle="collapse" href="#collapse1"><i class="fa fa-cog faa-spin animated faa-slow"></i></a></span>
                <?php
            }
            if (!Yii::$app->user->isGuest) {
                require_once('tpl_navigation.php');
            }
            ?>
            <?php if (!empty($portalNotification) && (int) $portalNotificationSetInterval > 0) { ?>
                <li class="dropdown bell-icon" id="notification-bell">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <span id="notification-count" class="badge"></span>
                    </a>
                    <ul class="dropdown-menu notification-box" id="notification-list">
                        <li><div id="notification-items"></div></li>
                    </ul>
                </li>
            <?php } ?>
        </div>
    </div>
</div>

<?php
if (!empty($portalNotification) && (int) $portalNotificationSetInterval > 0) {
    $ajaxUrl = Url::to(['/sms/default/get-latest-notification']);
    $deleteNoti = Url::to(['/sms/default/delete-notification']);
    $setInterval = ((int) $portalNotificationSetInterval) * 60 * 1000;
    $this->registerJs(<<<JS
$('#notification-list').hide();
let shownNotificationIds = [];
let loginTime = new Date();
var cnt = 0;
getNotification();
function getNotification() {
    $.ajax({
        url: "{$ajaxUrl}",
        type: "GET",
        data: { shown: shownNotificationIds },
        success: function(response) {
            if (response && response.length > 0) {
                var html = '';
                response.forEach(function(noti) {
                    var notiTime = new Date(noti.datetime);
                    var isNew = (noti.send_status == 0 && notiTime > loginTime);
                        if(noti.send_status == 0){
                            cnt++;
                            if (isNew) {
                            showFlashMessage(noti.message);
                        }
                    }
                    shownNotificationIds.push(noti.id);
                    html += '<div class="notification-msg">' +
                                '<span class="notification-text ' + (isNew ? 'new-notification' : '') + '">' + noti.message + '</span>' +
                                '<a href="#" class="delete-noti" id="'+noti.id+'" style="color:red;"><i class="fa fa-trash"></i></a>' +
                            '</div>';
                });
                if(cnt != 0){
                    $('#notification-count').text(cnt).show(); 
                }
                $('#notification-list').prepend(html);
            }
        }
    });
}

setInterval(function () {
    getNotification();
}, {$setInterval});
// On bell icon click
$('#notification-bell').on('click', function() {
    $('#notification-list').show();
    $('#notification-count').hide();
    var count = $('#notification-count').text();
        if (count !== '' && count != 0) {
            $.ajax({
                url: "{$ajaxUrl}",
                type: "POST",
            success: function(data) {
                $('#notification-count').text('');
                    cnt = 0;
                }
            });
        }
});
                
// Delete icon click
$(document).on('click', '.delete-noti', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    $.ajax({
        url: "{$deleteNoti}?id=" + id,
        type: "POST",
        success: function(res) {
            if (res.success) {
                $('#'+id).parent('div.notification-msg').remove();
            }
            if($('#notification-list .notification-msg').length == 0){
                $('#notification-list').hide();
            }
        }
    });
});

// Close dropdown if clicked outside
$(document).click(function(e) {
    if ($('#notification-list').is(':visible')) {
        if (!$(e.target).closest('#notification-bell, #notification-list').length) {
            $('#notification-list').hide();
            $('.new-notification').removeClass('new-notification');
        }
    }
});

function showFlashMessage(message) {
    const flash = $('<div class="flash-message"></div>').text(message);
    let flashCount = $('.flash-message').length; 
    let maxMessagesOnScreen = 20; 
    let topPosition = 20 + (flashCount % maxMessagesOnScreen) * 40;
    if (flashCount >= maxMessagesOnScreen) {
        topPosition = 20 + ((flashCount - maxMessagesOnScreen) % maxMessagesOnScreen) * 40;
    }
    $('body').append(flash);
    flash.css({
        position: 'fixed',
        top: topPosition + 'px',
        right: '20px',
        background: '#28a745',
        color: '#fff',
        padding: '10px 20px',
        marginTop: '10px',
        borderRadius: '5px',
        boxShadow: '0 0 10px rgba(0,0,0,0.2)',
        zIndex: 9999,
        display: 'none'
    });

    flash.fadeIn(400);

    setTimeout(function () {
        flash.fadeOut(500, function () {
            $(this).remove();
        });
    }, 5000); // show for 5 seconds
}

JS
    );
}
?>