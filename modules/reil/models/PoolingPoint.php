<?php

namespace app\modules\reil\models;

use Yii;

/**
 * This is the model class for table "PoolingPoint".
 *
 * @property string $PlantCode
 * @property string $PlantName
 * @property string $BMCCode
 * @property string $BMCName
 * @property string $PPCode
 * @property string $PPName
 * @property string $VillageCode
 * @property string $IMEINo
 * @property string $DPU_INIT_DTTM
 * @property integer $RATEID_B
 * @property integer $RATEID_C
 * @property integer $DPU_RATE_ID_B
 * @property integer $DPU_RATE_ID_C
 * @property string $DPURate_update_DTTM_B
 * @property string $DPURate_update_DTTM_C
 * @property string $PPACTIVE
 * @property string $DPU_MEM_UPDATION_REQUIRED
 * @property string $MID
 * @property string $DPU_MID
 * @property string $DPUMem_Update_DTTM
 * @property string $UPDATEDBY
 * @property string $UPDATEDON
 */
class PoolingPoint extends \yii\db\ActiveRecord
{
    
    public static function getDb() {
       return Yii::$app->get('reil'); // reil database
   }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PoolingPoint';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PlantCode', 'BMCCode', 'PPCode'], 'required'],
            [['PlantCode', 'PlantName', 'BMCCode', 'BMCName', 'PPCode', 'PPName', 'VillageCode', 'IMEINo', 'PPACTIVE', 'DPU_MEM_UPDATION_REQUIRED', 'MID', 'DPU_MID', 'UPDATEDBY'], 'string'],
            [['DPU_INIT_DTTM', 'DPURate_update_DTTM_B', 'DPURate_update_DTTM_C', 'DPUMem_Update_DTTM', 'UPDATEDON'], 'safe'],
            [['RATEID_B', 'RATEID_C', 'DPU_RATE_ID_B', 'DPU_RATE_ID_C'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PlantCode' => Yii::t('app', 'Plant Code'),
            'PlantName' => Yii::t('app', 'Plant Name'),
            'BMCCode' => Yii::t('app', 'Bmccode'),
            'BMCName' => Yii::t('app', 'Bmcname'),
            'PPCode' => Yii::t('app', 'Ppcode'),
            'PPName' => Yii::t('app', 'Ppname'),
            'VillageCode' => Yii::t('app', 'Village Code'),
            'IMEINo' => Yii::t('app', 'Imeino'),
            'DPU_INIT_DTTM' => Yii::t('app', 'Dpu  Init  Dttm'),
            'RATEID_B' => Yii::t('app', 'Rateid  B'),
            'RATEID_C' => Yii::t('app', 'Rateid  C'),
            'DPU_RATE_ID_B' => Yii::t('app', 'Dpu  Rate  Id  B'),
            'DPU_RATE_ID_C' => Yii::t('app', 'Dpu  Rate  Id  C'),
            'DPURate_update_DTTM_B' => Yii::t('app', 'Dpurate Update  Dttm  B'),
            'DPURate_update_DTTM_C' => Yii::t('app', 'Dpurate Update  Dttm  C'),
            'PPACTIVE' => Yii::t('app', 'Ppactive'),
            'DPU_MEM_UPDATION_REQUIRED' => Yii::t('app', 'Dpu  Mem  Updation  Required'),
            'MID' => Yii::t('app', 'Mid'),
            'DPU_MID' => Yii::t('app', 'Dpu  Mid'),
            'DPUMem_Update_DTTM' => Yii::t('app', 'Dpumem  Update  Dttm'),
            'UPDATEDBY' => Yii::t('app', 'Updatedby'),
            'UPDATEDON' => Yii::t('app', 'Updatedon'),
        ];
    }

    /**
     * @inheritdoc
     * @return PoolingPointQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PoolingPointQuery(get_called_class());
    }
}

