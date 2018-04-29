<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[TblStaffSalaryProcessing]].
 *
 * @see TblStaffSalaryProcessing
 */
class TblStaffSalaryProcessingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblStaffSalaryProcessing[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblStaffSalaryProcessing|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
