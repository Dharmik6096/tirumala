<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblHeadLoadCriteria]].
 *
 * @see TblHeadLoadCriteria
 */
class TblHeadLoadCriteriaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblHeadLoadCriteria[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadCriteria|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
