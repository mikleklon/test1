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
    $user = $userModels::findIdentity(Yii::$app->user->getId());
    echo "Ваша ссылка: ".Yii::$app->homeUrl."?ref=".$user->referelCode."<br/>";
    if($user->referal > 0 ) {
        $userReferel = $userModels::findIdentity($user->referal);
        echo "Вас пригласил " . $userReferel->username."<br/>";
    }
    echo "Вы пригласили: <br/>";
    foreach($user->findRef() as $c=>$us){
        echo ($c+1).". ".$us->username."<br/>";
    }

}?>
    </div>
</div>
