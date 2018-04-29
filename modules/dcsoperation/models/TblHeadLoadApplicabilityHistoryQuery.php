<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblHeadLoadApplicabilityHistory]].
 *
 * @see TblHeadLoadApplicabilityHistory
 */
class TblHeadLoadApplicabilityHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblHeadLoadApplicabilityHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadApplicabilityHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
