<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_dashboard_user_widgets".
 *
 * @property integer $dashboard_user_widget_id
 * @property string $user_id
 * @property string $postion_farmer
 * @property string $postion_rmrd
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDashboardUserWidgets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dashboard_user_widgets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'string', 'max' => 14],
            [['position_farmer', 'position_rmrd', 'is_farmer_popup', 'is_rmrd_popup'],'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dashboard_user_widget_id' => 'Dashboard User Widget ID',
            'user_id' => 'User ID',
            'position_farmer' => 'Position Farmer',
            'position_rmrd' => 'Position Rmrd',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function getDashboardUserWidgets(){
        return $this->find()->where(['user_id' => Yii::$app->session->get('UserCode')])->one();
    }
}
