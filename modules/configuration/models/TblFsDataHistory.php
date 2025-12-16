<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_fs_data_history".
 *
 * @property integer $id
 * @property integer $fs_data_code
 * @property string $union_code
 * @property string $dcs_code
 * @property string $dsrn
 * @property string $dtyp
 * @property string $dlock
 * @property string $dscch
 * @property string $dsbch
 * @property string $dsmch
 * @property string $dsfd
 * @property string $dssd
 * @property string $dssdm
 * @property string $dscc
 * @property string $dsai
 * @property string $dsas
 * @property string $dsmf
 * @property string $dsms
 * @property string $dsht
 * @property string $dsct
 * @property string $docfo
 * @property string $docso
 * @property string $docwo
 * @property string $docdo
 * @property string $docpo
 * @property string $doclo
 * @property string $dobfo
 * @property string $dobso
 * @property string $dobwo
 * @property string $dobdo
 * @property string $dobpo
 * @property string $doblo
 * @property string $domfo
 * @property string $domso
 * @property string $domwo
 * @property string $domdo
 * @property string $dompo
 * @property string $domlo
 * @property string $dpp1
 * @property string $dpp2
 * @property string $dpp3
 * @property string $download_datetime
 * @property string $processed_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblFsDataHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_fs_data_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fs_data_code', 'originating_type'], 'integer'],
            [['download_datetime', 'processed_datetime', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['union_code'], 'string', 'max' => 3],
            [['dcs_code'], 'string', 'max' => 20],
            [['dsrn', 'dtyp', 'dlock', 'dscch', 'dsbch', 'dsmch', 'dsfd', 'dssd', 'dssdm', 'dscc', 'dsai', 'dsas', 'dsmf', 'dsms', 'dsht', 'dsct', 'docfo', 'docso', 'docwo', 'docdo', 'docpo', 'doclo', 'dobfo', 'dobso', 'dobwo', 'dobdo', 'dobpo', 'doblo', 'domfo', 'domso', 'domwo', 'domdo', 'dompo', 'domlo', 'dpp1', 'dpp2', 'dpp3'], 'string', 'max' => 45],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['operation_type'], 'string', 'max' => 10],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'fs_data_code' => Yii::t('app', 'Fs Data Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'dsrn' => Yii::t('app', 'Dsrn'),
            'dtyp' => Yii::t('app', 'Dtyp'),
            'dlock' => Yii::t('app', 'Dlock'),
            'dscch' => Yii::t('app', 'Dscch'),
            'dsbch' => Yii::t('app', 'Dsbch'),
            'dsmch' => Yii::t('app', 'Dsmch'),
            'dsfd' => Yii::t('app', 'Dsfd'),
            'dssd' => Yii::t('app', 'Dssd'),
            'dssdm' => Yii::t('app', 'Dssdm'),
            'dscc' => Yii::t('app', 'Dscc'),
            'dsai' => Yii::t('app', 'Dsai'),
            'dsas' => Yii::t('app', 'Dsas'),
            'dsmf' => Yii::t('app', 'Dsmf'),
            'dsms' => Yii::t('app', 'Dsms'),
            'dsht' => Yii::t('app', 'Dsht'),
            'dsct' => Yii::t('app', 'Dsct'),
            'docfo' => Yii::t('app', 'Docfo'),
            'docso' => Yii::t('app', 'Docso'),
            'docwo' => Yii::t('app', 'Docwo'),
            'docdo' => Yii::t('app', 'Docdo'),
            'docpo' => Yii::t('app', 'Docpo'),
            'doclo' => Yii::t('app', 'Doclo'),
            'dobfo' => Yii::t('app', 'Dobfo'),
            'dobso' => Yii::t('app', 'Dobso'),
            'dobwo' => Yii::t('app', 'Dobwo'),
            'dobdo' => Yii::t('app', 'Dobdo'),
            'dobpo' => Yii::t('app', 'Dobpo'),
            'doblo' => Yii::t('app', 'Doblo'),
            'domfo' => Yii::t('app', 'Domfo'),
            'domso' => Yii::t('app', 'Domso'),
            'domwo' => Yii::t('app', 'Domwo'),
            'domdo' => Yii::t('app', 'Domdo'),
            'dompo' => Yii::t('app', 'Dompo'),
            'domlo' => Yii::t('app', 'Domlo'),
            'dpp1' => Yii::t('app', 'Dpp1'),
            'dpp2' => Yii::t('app', 'Dpp2'),
            'dpp3' => Yii::t('app', 'Dpp3'),
            'download_datetime' => Yii::t('app', 'Download Datetime'),
            'processed_datetime' => Yii::t('app', 'Processed Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
