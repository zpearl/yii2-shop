<?php
use yii\helpers\Html;

$this->title = 'Создать производителя';
$this->params['breadcrumbs'][] = ['label' => 'Производители', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\zpearl\shop\assets\BackendAsset::register($this);
?>
<div class="producer-create">
    <div class="shop-menu">
        <?=$this->render('../parts/menu');?>
    </div>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
