<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsSubcenterMiscHistory]].
 *
 * @see TblDcsSubcenterMiscHistory
 */
class TblDcsSubcenterMiscHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsSubcenterMiscHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsSubcenterMiscHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
