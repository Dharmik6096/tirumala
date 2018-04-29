<?php

namespace app\modules\setting\models;

/**
 * This is the ActiveQuery class for [[TblFrmObjects]].
 *
 * @see TblFrmObjects
 */
class TblFrmObjectsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFrmObjects[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFrmObjects|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
