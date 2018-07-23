<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CollectionFarmerCreamy]].
 *
 * @see CollectionFarmerCreamy
 */
class CollectionFarmerCreamyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CollectionFarmerCreamy[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CollectionFarmerCreamy|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
