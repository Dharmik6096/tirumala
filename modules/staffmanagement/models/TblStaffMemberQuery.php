<?php

namespace app\modules\staffmanagement\models;

/**
 * This is the ActiveQuery class for [[TblStaffMember]].
 *
 * @see TblStaffMember
 */
class TblStaffMemberQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblStaffMember[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblStaffMember|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
