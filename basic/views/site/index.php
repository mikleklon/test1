<?php

/* @var $this yii\web\View */

$this->title = 'Start';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Hello!</h1>
    </div>

    <div class="body-content">
<?php
$userModels = new \app\models\User();
if(Yii::$app->user->isGuest) {

    $userReferel = $userModels::checkReferelCode();
    if (!empty($userReferel)) {
        echo "Вас пригласил " . $userReferel->username;
    } else {
        echo "Вы пришли сами";
    }
}else{
    echo "Ваша ссылка: ".Yii::$app->homeUrl."?ref=".$userModels::findIdentity(Yii::$app->user->getId())->referelCode;
}?>
    </div>
</div>
