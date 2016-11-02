<?php
namespace zpearl\shop;

use yii;

class Module extends \yii\base\Module
{
    public $adminRoles = ['admin', 'superadmin'];
    public $modelMap = [];
    public $defaultTypeId = null;
    public $priceType = null; //callable, возвращающая type_id цены
    public $categoryUrlPrefix = '/shop/category/view';
    public $productUrlPrefix = '/shop/product/view';
    public $oneC = null;
    public $userModel = null;
    public $users = [];
    public $menu = [
        [
            'label' => 'Товары',
            'url' => ['/shop/product/index'],
        ],
        [
            'label' => 'Категории',
            'url' => ['/shop/category/index'],
        ],
        [
            'label' => 'Производители',
            'url' => ['/shop/producer/index'],
        ],
        [
            'label' => 'Склады',
            'url' => ['/shop/stock/index'],
        ],
        [
            'label' => 'Типы цен',
            'url' => ['/shop/price-type/index'],
        ],
    ];
	
    const EVENT_PRODUCT_CREATE = 'create_product';
    const EVENT_PRODUCT_DELETE = 'delete_product';
    const EVENT_PRODUCT_UPDATE = 'update_product';
    
    public function init()
    {
        if(empty($this->modelMap)) {
            $this->modelMap = [
                'product' => '\zpearl\shop\models\Product',
                'category' => '\zpearl\shop\models\Category',
                'incoming' => '\zpearl\shop\models\Incoming',
                'outcoming' => '\zpearl\shop\models\Outcoming',
                'producer' => '\zpearl\shop\models\Producer',
                'price' => '\zpearl\shop\models\Price',
                'stock' => '\zpearl\shop\models\Stock',
                'modification' => '\zpearl\shop\models\Modification',
            ];
        }
        
        if(!$this->userModel) {
            if($user = yii::$app->user->getIdentity()) {
                $this->userModel = $user::className();
            }
        }
        
        if(is_callable($this->users)) {
            $func = $this->users;
            $this->users = $func();
        }
        
        parent::init();
    }
    
    //возвращает type_id цены, которую стоит отобразить покупателю
    public function getPriceTypeId($product = null)
    {
        if(is_callable($this->priceType))
        {
            $priceType = $this->priceType;
            return $values($product);
        }
        
        return $this->defaultTypeId;
    }
    
    public function getService($key)
    {
        $model = $this->modelMap[$key];
        
        return new $model;
    }
}
