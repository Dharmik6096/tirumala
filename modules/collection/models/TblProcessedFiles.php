<?php

namespace app\modules\collection\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblDcs;
/**
 * This is the model class for table "tbl_processed_files".
 *
 * @property integer $process_id
 * @property string $cp_code
 * @property string $file_path
 * @property string $vendor_id
 * @property string $processed_at
 *
 * @property BiplFtpCollection[] $biplFtpCollections
 */
class TblProcessedFiles extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_processed_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cp_code', 'file_path', 'vendor_id','dcs_code','union_code'], 'string'],
            [['processed_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'process_id' => Yii::t('app', 'Process ID'),
            'cp_code' => Yii::t('app', 'Cp Code'),
            'file_path' => Yii::t('app', 'File Path'),
            'vendor_id' => Yii::t('app', 'Vendor ID'),
            'processed_at' => Yii::t('app', 'Process Date'),
            'dcs_code'=>Yii::t('app', 'Society'),
            'union_code'=>Yii::t('app', 'Union')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiplFtpCollections()
    {
        return $this->hasMany(BiplFtpCollection::className(), ['process_id' => 'process_id']);
    }

    /**
     * @inheritdoc
     * @return TblProcessedFilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblProcessedFilesQuery(get_called_class());
    }
    
    public function getProcessedFiles($vendor, $cp_code='')
    {
        $pfiles=[];
        if(!empty($vendor))
        {
            $query=  $this->find()->where(['vendor_id'=>$vendor]);
            if(!empty($cp_code))
            {
                $query->andWhere(['cp_code'=>$cp_code]);
            }
            $pfiles=$query->all();
            if(!empty($pfiles))
                $pfiles=  ArrayHelper::getColumn($pfiles, 'file_path');
        }
        return $pfiles;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
}
