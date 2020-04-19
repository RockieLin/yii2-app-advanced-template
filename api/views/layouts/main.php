<?php

$this->beginPage()

?>
<!DOCTYPE html>
<html>

    <head>
        <title>API</title>
        <?php $this->head() ?>

        <meta charset="utf-8">
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?= $content; ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>