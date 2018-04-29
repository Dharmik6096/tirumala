<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[TblStaffMemberLocal]].
 *
 * @see TblStaffMemberLocal
 */
class TblStaffMemberLocalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblStaffMemberLocal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblStaffMemberLocal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
