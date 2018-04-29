<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[TblStaffSalary]].
 *
 * @see TblStaffSalary
 */
class TblStaffSalaryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblStaffSalary[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblStaffSalary|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
