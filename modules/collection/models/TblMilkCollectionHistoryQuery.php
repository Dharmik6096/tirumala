<?php

namespace app\modules\collection\models;

/**
 * This is the ActiveQuery class for [[TblMilkCollectionHistory]].
 *
 * @see TblMilkCollectionHistory
 */
class TblMilkCollectionHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMilkCollectionHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMilkCollectionHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
