<!--header starts-->
<?php

use yii\helpers\Url;

$logo = $this->theme->getUrl('/assets/images/logo.png');
$eipl_code = Yii::$app->session->get('eiplCode');
$logo_code = Yii::$app->session->get('organization_logo');
$logo_image = !empty($logo_code) ? $logo_code : strtolower($eipl_code) . '.png';
$new_logo = $this->theme->getUrl('/assets/images/union_logo/') . $logo_image;
$dir_path = Yii::$app->basePath . '/' . substr(Yii::$app->params['logo_path'], 1) . $logo_image;
$logo = file_exists($dir_path) ? $new_logo : $logo;
?>

<div class="navbar navbar-fixed-top menu-wrap">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= Url::to(['/site/dashboard']) ?>"><img src="<?= $logo ?>" alt='<?= Yii::t('app', 'Company Logo') ?>' class="logo img-responsive"/></a>
            <li class="dropdown list-none" id="notification-bell">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell"></i>
                    <span id="notification-count" class="badge"></span>
                </a>
                <ul class="dropdown-menu notification-box" id="notification-list">
                    <li><div id="notification-items"></div></li>
                </ul>
            </li>
        </div>
        <div class="navbar-collapse collapse navbar-responsive-collapse">
            <?php if (false && (Url::home() . 'site' == Yii::$app->request->url || Url::home() . 'site/index' == Yii::$app->request->url)) { ?>
                <span class="pull-right dashboard_set_icon"><a data-toggle="collapse" href="#collapse1"><i class="fa fa-cog faa-spin animated faa-slow"></i></a></span>
                        <?php
                    }
                    if (!Yii::$app->user->isGuest) {
                        require_once('tpl_navigation.php');
                    }
                    ?>
        </div>
    </div>
</div>

<?php
$ajaxUrl = Url::to(['/sms/default/get-latest-notification']);
$deleteNoti = Url::to(['/sms/default/delete-notification']);

$this->registerJs(<<<JS
$('#notification-list').hide();
let shownNotificationIds = [];
let isPageLoad = true;
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
                    if(noti.send_status == 0 || noti.send_status == 1){
                        cnt++;
                        if(noti.send_status == 0){
                            if(isPageLoad){
                                setTimeout(function () {
                                    showFlashMessage(noti.message);
                                }, delay);
                                delay += 6000; // 5 sec display + 1 sec gap
                            } else {
                                showFlashMessage(noti.message);
                            }
                        }
                    }
                        shownNotificationIds.push(noti.id);
                    html += '<div class="notification-msg" style="display:flex; justify-content:space-between; align-items:center; padding:4px 10px;">' +
                                '<span>' + noti.message + '</span>' +
                                '<a href="#" class="delete-noti" id="'+noti.id+'" style="color:red;"><i class="fa fa-trash"></i></a>' +
                            '</div>';
                });
                if(cnt != 0){
                    $('#notification-count').text(cnt); 
                }
                $('#notification-list').append(html);
            }
        }
    });
}

setInterval(function () {
    getNotification();
}, 10000);
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
            if($('#notification-list').html().trim() == ''){
                $('#notification-list').hide();
            }
        }
    });
});

// Close dropdown if clicked outside
$(document).click(function(e) {
    if (!$(e.target).closest('#notification-bell').length) {
        $('#notification-list').hide();
    }
});
  


function showFlashMessage(message) {
    const flash = $('<div class="flash-message"></div>').text(message);
    $('body').append(flash);
    flash.css({
        position: 'fixed',
        top: '20px',
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
?>