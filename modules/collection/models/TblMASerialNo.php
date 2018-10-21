<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_MA_SerialNo".
 *
 * @property integer $id
 * @property string $BMCCode
 * @property string $PPCode
 * @property string $DtDate
 * @property string $shift
 * @property string $serialno
 * @property string $updatedby
 * @property string $updateddate
 * @property integer $ref_id
 */
class TblMASerialNo extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MA_SerialNo';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['BMCCode', 'PPCode', 'DtDate', 'serialno', 'updatedby'], 'required'],
            [['BMCCode', 'PPCode', 'shift', 'serialno', 'updatedby'], 'string'],
            [['DtDate', 'updateddate'], 'safe'],
            [['ref_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'BMCCode' => Yii::t('app', 'Bmccode'),
            'PPCode' => Yii::t('app', 'Ppcode'),
            'DtDate' => Yii::t('app', 'Dt Date'),
            'shift' => Yii::t('app', 'Shift'),
            'serialno' => Yii::t('app', 'Serialno'),
            'updatedby' => Yii::t('app', 'Updatedby'),
            'updateddate' => Yii::t('app', 'Updateddate'),
            'ref_id' => Yii::t('app', 'Ref ID'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'PPCode']);
    }

}
