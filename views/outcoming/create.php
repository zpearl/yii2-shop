<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Новое отправление';
$this->params['breadcrumbs'][] = $this->title;

zpearl\shop\assets\CreateOutcomingAsset::register($this);
\zpearl\shop\assets\BackendAsset::register($this);
?>

<div class="incoming-create">
    <div class="shop-menu">
        <?=$this->render('../parts/menu');?>
    </div>
    
    <?php if(Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>
    
    <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <input class="new-input" data-info-service="<?=Url::toRoute(['/shop/product/product-info']);?>" type="text" value="" placeholder="Код или артикул + Enter" style="width: 300px;" />
        </div>
        <div id="incoming-list" style="width: 800px;">
        </div>
        
        <div class="form-group">
            <?= Html::submitButton('Добавить отправление', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>