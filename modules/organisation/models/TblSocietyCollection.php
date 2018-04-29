<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_society_collection".
 *
 * @property integer $collection_id
 * @property string $dcs_code
 * @property integer $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblSocietyCollection extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_society_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'created_by', 'updated_by'], 'string'],
            [['status'], 'integer'],
            [['created_at', 'updated_at','to_date'], 'safe'],
            [['from_date'], 'required'],
            [['remarks'], 'required', 'on'=>'update_collection'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'collection_id' => Yii::t('app', 'Collection ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'from_date' => Yii::t('app', 'Wef Date'),
            'to_date    ' => Yii::t('app', 'To Date'),
            'remarks' => Yii::t('app', 'Remarks'),
        ];
    }
    
    public function getDcsCode(){
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
    
}
