<?php

namespace app\commands;

use common\services\InboxParseService;

class AmcsparseinboxController extends \yii\console\Controller {

    public function actionParseInboxData() {
        $inboxParseService = new InboxParseService();
        while (true) {
            $inboxParseService->InboxParsing();
            sleep(2);
        }
    }

}
