前台首頁 <?= Yii::$app->language; ?>
<div>
    <?php foreach (Yii::t("app", "languageSet") as $_code => $_label): ?>
        <a href="<?= Yii::$app->tool->toCurrent(["language" => $_code]); ?>"><?= $_label; ?></a>
    <?php endforeach; ?>
</div>

<ul>
    <li>
        <?php if (Yii::$app->user->isGuest): ?>
            <a href="/user/login">登入</a>
            <br/>
            <a href="/user/register">註冊</a>
        <?php else: ?>
            <a href="/user/logout">登出</a>
        <?php endif; ?>
    </li>
    <li>
        <a href="/announce">公告</a>
    </li>
</ul>