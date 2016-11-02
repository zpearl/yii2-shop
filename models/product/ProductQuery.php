<?php
namespace zpearl\shop\models\product;

use zpearl\shop\models\Category;
use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    function behaviors()
    {
       return [
           'filter' => [
               'class' => 'pistol88\filter\behaviors\Filtered',
           ],
       ];
    }
    
    public function available()
    {
         return $this->andwhere("`available` = 'yes'");
    }
    
    public function category($childCategoriesIds)
    {
         return $this->andwhere(['category_id' => $childCategoriesIds]);
    }
}