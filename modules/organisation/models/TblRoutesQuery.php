<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblRoutes]].
 *
 * @see TblRoutes
 */
class TblRoutesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRoutes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRoutes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
