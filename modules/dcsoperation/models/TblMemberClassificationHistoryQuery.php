<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblMemberClassificationHistory]].
 *
 * @see TblMemberClassificationHistory
 */
class TblMemberClassificationHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMemberClassificationHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMemberClassificationHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
