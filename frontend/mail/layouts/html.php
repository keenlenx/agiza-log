<?php
/* @var $this yii\web\View */
/* @var $content string */
?>
<html>
<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <!-- Header content -->
                <h1>Email Header</h1>
            </td>
        </tr>
        <tr>
            <td>
                <!-- Main content -->
                <?= $content ?>
            </td>
        </tr>
        <tr>
            <td>
                <!-- Footer content -->
                <p>Email Footer</p>
            </td>
        </tr>
    </table>
</body>
</html>
