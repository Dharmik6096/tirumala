<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\View;
?>
<div class="chatboard-section">
    <div class="chatboard" id="chatboard">
        <?php
        foreach ($feedbackMasterTxn as $key => $value) {
            $date = Yii::$app->controls->view_date($value->feedback_message_datetime);
            $dateMonth = Yii::$app->controls->view_date($value->feedback_message_datetime, 'php:d-M');
            $time = Yii::$app->controls->view_time($value->feedback_message_datetime);
            $message_time = $dateMonth . ' ' . $time;
            if ($date == date('d-m-Y')) {
                $message_time = $time;
            }
            if ($value->originator_type != "admin") {
                ?>
                <div class="received-section">                    
                    <div class="received">
                        <span class="messager_name"><strong><?php echo ucfirst($value->name); ?></strong></span>
                        <span class="message-time"><?php echo $message_time; ?></span>
                        <p class="feedback_message">
                            <?php 
                            $urlRegex = '/(https?|http):\/\/[^\s]+/';
                            $message = nl2br(Yii::$app->general->asciiToTextConvert($value->feedback_message));
                            if (preg_match($urlRegex, $message, $matches)) {
                                $url = $matches[0];
                                $message = '<a href="'.$url.'" target="_blank">'.$message.'</a>';
                            }
                            echo $message;?>
                        </p>                      
                        <?php
                        if(!empty($value->file_path)){ ?>
                            <div class="feedback_image">
                                <?php
                                echo $value->file_path; ?>
                            </div>
                            <?php
                        } ?>
                    </div>
                    <div class="triangle"></div>
                </div>

            <?php } else {
                ?>
                <div class="sent-section">
                    <div class="sent">
                        <span class="messager_name"><strong><?php echo ucfirst($value->name); ?></strong></span>
                        <span class="message-time"><?php echo $message_time; ?></span>
                        <p class="feedback_message">
                            <?php 
                            $urlRegex = '/(https?|http):\/\/[^\s]+/';
                            $message = nl2br(Yii::$app->general->asciiToTextConvert($value->feedback_message));
                            if (preg_match($urlRegex, $message, $matches)) {
                                $url = $matches[0];
                                $message = '<a href="'.$url.'" target="_blank">'.$message.'</a>';
                            }
                            echo $message;?>
                        </p>                        
                        <?php
                        if(!empty($value->file_path)){ ?>
                            <div class="feedback_image">
                                <?php
                                echo $value->file_path; ?>
                            </div>
                            <?php
                        } ?>
                    </div>
                    <div class="triangle"></div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <?php if ($model->feedback_status != 2) { ?>
        <div class="message-section">
            <?= Html::hiddenInput('eipl_app_feedback_master_code', $model->eipl_app_feedback_master_code, ['id' => 'eipl_app_feedback_master_code']); ?>
            <div class="form-group message-box">
                <p class="emoji-picker-container" id="emoji-picker-container">
                    <?= Html::textarea("message", "", ['id' => 'message', 'class' => 'form-control emoji-picker-container', 'data-emojiable' => 'true', 'data-emoji-input' => 'unicode', 'placeholder' => 'Please Message Type Here...']) ?>
                </p>
            </div>
            <!--<div class="button-section">-->
                <div class="form-group attachment no_pointer message-btn" id="attachment">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                </div>
                <div class="form-group msg-check message-btn">
                    <?= Html::checkbox("close", false, ['id' => 'close', 'class' => 'msg-checkbox']) ?>
                    <?= Html::label('Close', 'close') ?>
                </div>
                <div class="form-group btn-margin message-btn">
                    <?= Html::a("send", null, ['href' => 'javascript:void(0);', 'class' => 'btn btn-primary', 'id' => 'send-message']) ?>
                </div>
            <!--</div>-->
        </div>
        <?php
            echo $this->render('@app/modules/feedback\views\tbl-eipl-app-feedback-master\attachment_modal', ['model' => $model]);
        ?>
    <?php } else {
        ?>
        <div class="message-section"><p class="empty_message">This feedback is closed.</p></div>
        <?php
    }
    ?>
</div>

<?php
$script = "
    $(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
            emojiable_selector: '[data-emojiable=true]',
            assetsPath: '" . $this->theme->getUrl('/assets/emoji/img') . "',
            popupButtonClasses: 'far fa-smile'
        });

        window.emojiPicker.discover();
    });
    var objDiv = document.getElementById('chatboard');
    objDiv.scrollTop = objDiv.scrollHeight;
    $('#send-message').click(function(){
        $('#send-message').attr('disabled',true);
        $('#send-message').css('pointer-events','none');
        var eipl_app_feedback_master_code = $('#eipl_app_feedback_master_code').val();
        var message = $('#message').val();
        var is_close = 0;
        if($('#close').prop('checked') == true){
            is_close = 1;
        }
        if(message == ''){
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-info\'></i></div><div class=\'col-sm-10 padding-left-0\'><span>Message is required</span></div></div>\");
            return false;
        }
        $.ajax({
            type: 'post',
            url: '" . Url::to(['/feedback/tbl-eipl-app-feedback-master/send-message']) . "',
            data: {is_close:is_close, message:message, eipl_app_feedback_master_code: eipl_app_feedback_master_code},
            success: function(data) {
                var obj1 = $.parseJSON(data);
                if(obj1.status == 'success'){
                    var currentdate = new Date();
                    var hours = currentdate.getHours() < 10 ? '0'+currentdate.getHours() : currentdate.getHours();
                    var minutes = currentdate.getMinutes() < 10 ? '0'+currentdate.getMinutes() : currentdate.getMinutes();
                    var time = hours + ':' +minutes;
                    var html = '<div class=\'sent-section\'><div class=\'sent\'><span class=\'messager_name\'><strong>'+obj1.data['name']+'</strong></span><span class=\'message-time\'>'+time+'</span><br><pre>'+$('#message').val()+'</pre></div><div class=\'triangle\'></div></div>';
                    $('#chatboard').append(html);
                    $('#message').val('');
                    $('#send-message').attr('disabled',false);
                    $('#send-message').removeAttr('style');
                    $('#emoji-picker-container .emoji-picker-container').text('');
                    if(obj1.data['is_close'] == true){
                        $('.message-section').html('<p class=\'empty_message\'>This feedback is closed.</p>');
                    }
                    objDiv.scrollTop = objDiv.scrollHeight;
                }
            }
        });
    });
    $('#attachment').on('click',function(e){
        $('#attachmentModal').modal('toggle');
    });
    ";
$this->registerJs($script, View::POS_END, 'tbl-eipl-app-feedback-master');
?>