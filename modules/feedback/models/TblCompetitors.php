<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_competitors".
 *
 * @property integer $competitor_id
 * @property string $competitor_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCompetitors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_competitors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['competitor_id','competitor_name','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'competitor_id' => Yii::t('app', 'Competitor ID'),
            'competitor_name' => Yii::t('app', 'Competitor Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
