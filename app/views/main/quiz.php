<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= DEFAULT_ENCODING_UPPERCASE ?>" />
    <link rel="shortcut icon" href="/favicon.ico?00001" type="image/x-icon">
    <link href="<?= APP_TEMPLATES ?>css/uikit.css?00001" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/chosen/chosen.css?00004" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/quiz_main.css?00004" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/nav.css?00001" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/font-awesome.min.css?00001" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/app_messages.css?00003" rel="stylesheet">

    <script src="<?= APP_TEMPLATES ?>js/nav.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/offcanvas.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/jquery-3.2.1.min.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/uikit.js"></script>

    <title><?= $pagetitle;?></title>
</head>
<body>
<table class="body" cellpadding="0" cellspacing="0" align="center">
    <tr id="header">
        <td>
            Header
        </td>
    </tr>
    <tr id="content">
        <td style="vertical-align: text-top">
            <div data-uk-grid class="uk-grid uk-grid-collapse">
                <div class="uk-width-1-1 uk-margin-large-bottom" align="center">

                    <div class="uk-width-8-10" id="q_container" align="left">

                        <table cellpadding="0" cellspacing="0" class="content">
                            <tr>
                                <td class="left">
                                    <div class="left">

                                    </div>
                                </td>
                                <td class="right">
                                    <div class="right"></div>
                                </td>
                            </tr>
                        </table>

                    </div>

                </div>
            </div>
        </td>
    </tr>
    <tr id="footer">
        <td align="center">
            Автоматизированная система тестирования, <?= date ('Y') ?>
        </td>
    </tr>
</table>
</body>
</html>