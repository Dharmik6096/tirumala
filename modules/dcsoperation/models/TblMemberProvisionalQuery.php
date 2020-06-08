<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblMemberProvisional]].
 *
 * @see TblMemberProvisional
 */
class TblMemberProvisionalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMemberProvisional[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMemberProvisional|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
