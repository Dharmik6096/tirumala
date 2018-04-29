<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[TblStaffMemberLocalHistory]].
 *
 * @see TblStaffMemberLocalHistory
 */
class TblStaffMemberLocalHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblStaffMemberLocalHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblStaffMemberLocalHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
