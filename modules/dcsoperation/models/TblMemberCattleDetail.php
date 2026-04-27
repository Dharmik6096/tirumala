<?php

namespace app\modules\dcsoperation\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_member_cattle_detail".
 *
 * @property integer $member_cattle_detail_code
 * @property integer $milky
 * @property integer $dry
 * @property integer $calf
 * @property integer $total
 * @property string $member_code
 * @property string $cattle_detail
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMemberCattleDetail extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member_cattle_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['milky', 'dry', 'calf', 'total', 'member_code', 'cattle_detail', 'originating_org_code', 'originating_org_type', 'originating_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_cattle_detail_code' => Yii::t('app', 'Member Cattle Detail Code'),
            'milky' => Yii::t('app', 'Milky'),
            'dry' => Yii::t('app', 'Dry'),
            'calf' => Yii::t('app', 'Calf'),
            'total' => Yii::t('app', 'Total'),
            'member_code' => Yii::t('app', 'Member Code'),
            'cattle_detail' => Yii::t('app', 'Cattle Detail'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
}
