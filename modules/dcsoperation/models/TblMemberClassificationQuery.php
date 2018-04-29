<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblMemberClassification]].
 *
 * @see TblMemberClassification
 */
class TblMemberClassificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMemberClassification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMemberClassification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
