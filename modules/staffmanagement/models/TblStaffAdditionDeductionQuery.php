<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[TblStaffAdditionDeduction]].
 *
 * @see TblStaffAdditionDeduction
 */
class TblStaffAdditionDeductionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblStaffAdditionDeduction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblStaffAdditionDeduction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
