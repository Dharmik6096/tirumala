<?php

namespace app\modules\globalmaster\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_committee".
 *
 * @property integer $committee_code
 * @property string $committee_name
 * @property string $local_name
 * @property string $election_date
 * @property string $formation_date
 * @property string $year
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblCommittee extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_committee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['election_date', 'formation_date', 'created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'integer'],
            [['committee_name', 'local_name'], 'string', 'max' => 45],
            [['year'], 'string', 'max' => 5],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'committee_code' => Yii::t('app', 'Committee Code'),
            'committee_name' => Yii::t('app', 'Committee Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'election_date' => Yii::t('app', 'Election Date'),
            'formation_date' => Yii::t('app', 'Formation Date'),
            'year' => Yii::t('app', 'Year'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
}
