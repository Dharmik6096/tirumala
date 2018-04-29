<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcsSubcenterMisc]].
 *
 * @see TblDcsSubcenterMisc
 */
class TblDcsSubcenterMiscQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsSubcenterMisc[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsSubcenterMisc|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
