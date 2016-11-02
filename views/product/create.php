<?php
use yii\helpers\Html;

$this->title = 'Добавить товар';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\zpearl\shop\assets\BackendAsset::register($this);
?>
<div class="product-create">
    <div class="shop-menu">
        <?=$this->render('../parts/menu');?>
    </div>
    
    <?= $this->render('_form', [
        'model' => $model,
        'priceTypes' => $priceTypes,
        'priceModel' => $priceModel,
    ]) ?>

</div>
