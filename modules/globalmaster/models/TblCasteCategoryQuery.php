<?php

namespace app\modules\globalmaster\models;
/**
 * This is the ActiveQuery class for [[TblCasteCategory]].
 *
 * @see TblCasteCategory
 */
class TblCasteCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblCasteCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblCasteCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
