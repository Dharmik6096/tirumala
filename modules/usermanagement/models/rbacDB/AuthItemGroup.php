<?php

namespace app\modules\usermanagement\models\rbacDB;

use Yii;

class AuthItemGroup extends \webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup {

    public function rules() {
        return [
            [['code', 'name'], 'required'],
            ['code', 'unique'],
            [['code'], 'string', 'max' => 64],
            [['code', 'name'], 'trim'],
            [['name'], 'string', 'max' => 255],
            ['code', 'match', 'pattern' => '/^(?![0-9]+$)/', 'message' => 'The Code cannot consist only of numbers. Please include letters.'],
        ];
    }

}
