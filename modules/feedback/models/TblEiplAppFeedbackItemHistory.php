<?php

namespace app\modules\feedback\models;

use Yii;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_eipl_app_feedback_item".
 *
 * @property integer $eipl_app_feedback_item_code
 * @property string $feedback_item_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_by
 */
class TblEiplAppFeedbackItemHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_feedback_item_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['eipl_app_feedback_item_code', 'feedback_item_name', 'created_by', 'updated_by'], 'safe'],
                [['created_at','updated_at'], 'safe'],
                [['created_at','updated_at', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

}
