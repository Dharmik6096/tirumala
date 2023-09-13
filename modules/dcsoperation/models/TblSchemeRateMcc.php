<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_scheme_rate_mcc".
 *
 * @property string $scheme_rate_mcc_code
 * @property string $scheme_rate_code
 * @property string $mcc_plant_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblSchemeRateMcc extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_rate_mcc';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['scheme_rate_mcc_code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'integer'],
            [['scheme_rate_mcc_code', 'scheme_rate_code'], 'safe'],
            [['mcc_plant_code'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['union_code'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'scheme_rate_mcc_code' => Yii::t('app', 'Scheme Rate Mcc Code'),
            'scheme_rate_code' => Yii::t('app', 'Scheme Rate Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getRateMcc($rateId) {
        $rateMccModel = $this->find()->where(['scheme_rate_code' => $rateId])->all();
        $rateMccData = ArrayHelper::map($rateMccModel, 'mcc_plant_code', 'mcc_plant_code');
        $rateMcc = implode(",", $rateMccData);
        return $rateMcc;
    }

}
