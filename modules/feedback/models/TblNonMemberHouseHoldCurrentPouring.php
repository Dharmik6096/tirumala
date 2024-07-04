<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_non_member_house_hold_current_pouring".
 *
 * @property integer $house_hold_current_pouring_id
 * @property integer $house_hold_visit_id
 * @property integer $competitor_id
 * @property string $milk_volume
 * @property string $milk_rate
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblNonMemberHouseHoldCurrentPouring extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_non_member_house_hold_current_pouring';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_hold_current_pouring_id','house_hold_visit_id','competitor_id','milk_volume','milk_rate','remarks','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'house_hold_current_pouring_id' => Yii::t('app', 'House Hold Current Pouring ID'),
            'house_hold_visit_id' => Yii::t('app', 'House Hold Visit ID'),
            'competitor_id' => Yii::t('app', 'Competitor'),
            'milk_volume' => Yii::t('app', 'Milk Volume'),
            'milk_rate' => Yii::t('app', 'Milk Rate'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getCompetitorCode() {
        return $this->hasOne(TblCompetitors::className(), ['competitor_id' => 'competitor_id']);
    }
}
