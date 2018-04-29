<?php

namespace app\modules\organisation\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_dpu_installation".
 *
 * @property integer $inst_code
 * @property string $inst_date
 * @property string $inst_by
 * @property string $remarks
 * @property string $attachment
 * @property string $dcs_code
 * @property string $union_code
 * @property string $soc_secretary
 * @property string $secretary_mobile
 * @property string $simcard_company
 * @property string $dpu_sim_mobile
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDpuInstallation extends ChildModel {
    
    public $old_attachment;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dpu_installation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['inst_date', 'created_at', 'updated_at'], 'safe'],
            [['inst_date', 'inst_by', 'soc_secretary', 'secretary_mobile', 'simcard_company', 'dpu_sim_mobile'], 'required'],
            [['secretary_mobile', 'dpu_sim_mobile'], function ($attribute, $params) {
            Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
        }, 'skipOnEmpty' => true],
            [['inst_by', 'remarks', 'attachment', 'dcs_code', 'union_code', 'soc_secretary', 'secretary_mobile', 'simcard_company', 'dpu_sim_mobile', 'created_by', 'updated_by'], 'string'],
            [['attachment'], 'file'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'inst_code' => Yii::t('app', 'Installation Code'),
            'inst_date' => Yii::t('app', 'Installation Date'),
            'inst_by' => Yii::t('app', 'Installation By'),
            'remarks' => Yii::t('app', 'Remarks'),
            'attachment' => Yii::t('app', 'Attachment'),
            'dcs_code' => Yii::t('app', 'Society'),
            'union_code' => Yii::t('app', 'Union'),
            'soc_secretary' => Yii::t('app', 'Society Secretary'),
            'secretary_mobile' => Yii::t('app', 'Secretary Mobile'),
            'simcard_company' => Yii::t('app', 'Simcard Company'),
            'dpu_sim_mobile' => Yii::t('app', 'Dpu Sim Mobile'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function uploadFile($attachment) {
        if (isset($attachment)) {
//            Yii::$app->general->checkDirectory(Yii::$app->params['dpu_docs_path']);
            // store the source file name
            $this->attachment = $attachment->name;
            $ext = (explode(".", $attachment->name));
            // generate a unique file name
            $files = \yii\helpers\FileHelper::findFiles(Yii::$app->params['dpu_docs_path'], ['only' => ['*.' . $ext[1]]]);
            if (isset($files[0])) {
                foreach ($files as $index => $file) {
                    $fileName = substr($file, strrpos($file, '/') + 2);
                    if ($this->attachment == $fileName) {
                        $fn = explode('.', $fileName);
                        $fn = $fn[0] . '(' . ($index + 1) . ').' . $fn[1];
                    }
                }
                return isset($fn) ? $fn : $this->attachment;
            }
            return $this->attachment;
        }
    }

    /**
     * @inheritdoc
     * @return TblDpuInstallationQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDpuInstallationQuery(get_called_class());
    }

}
