<?php

namespace app\modules\geo\models;

/**
 * This is the ActiveQuery class for [[TblVillages]].
 *
 * @see TblVillages
 */
class TblVillagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVillages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVillages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
