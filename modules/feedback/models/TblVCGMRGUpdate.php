<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_VCG_MRG_Update".
 *
 * @property integer $VCG_MRG_Update_id
 * @property integer $VCG_M_id
 * @property integer $MRG_M_id
 * @property string $month
 * @property string $description
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $flg_sentbox_entry
 * @property integer $originating_type
 */
class TblVCGMRGUpdate extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_MRG_Update';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_M_id', 'MRG_M_id', 'month', 'description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'VCG_MRG_Update_id' => Yii::t('app', 'Vcg Mrg Update ID'),
            'VCG_M_id' => Yii::t('app', 'Vcg M ID'),
            'MRG_M_id' => Yii::t('app', 'Mrg M ID'),
            'month' => Yii::t('app', 'Month'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
