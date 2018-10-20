<?php

namespace app\modules\creamy\models;

use Yii;

/**
 * This is the model class for table "tbl_DPUShiftEndSummary".
 *
 * @property integer $Id
 * @property string $BMCCode
 * @property string $VillageCode
 * @property string $dtdate
 * @property string $shift
 * @property integer $samplecount
 * @property string $Qty
 * @property string $fat
 * @property string $snf
 * @property string $amount
 * @property string $updatedby
 * @property string $updateddate
 */
class TblDpuShiftEndSummaryCreamy extends \yii\db\ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_DPUShiftEndSummary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['BMCCode', 'VillageCode', 'dtdate', 'shift', 'samplecount', 'Qty', 'fat', 'snf', 'amount', 'updatedby', 'updateddate'], 'required'],
            [['BMCCode', 'VillageCode', 'shift', 'updatedby'], 'string'],
            [['dtdate', 'updateddate'], 'safe'],
            [['samplecount'], 'integer'],
            [['Qty', 'fat', 'snf', 'amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'Id' => Yii::t('app', 'ID'),
            'BMCCode' => Yii::t('app', 'Bmccode'),
            'VillageCode' => Yii::t('app', 'Village Code'),
            'dtdate' => Yii::t('app', 'Dtdate'),
            'shift' => Yii::t('app', 'Shift'),
            'samplecount' => Yii::t('app', 'Samplecount'),
            'Qty' => Yii::t('app', 'Qty'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'amount' => Yii::t('app', 'Amount'),
            'updatedby' => Yii::t('app', 'Updatedby'),
            'updateddate' => Yii::t('app', 'Updateddate'),
        ];
    }

}
