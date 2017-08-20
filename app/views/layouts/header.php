<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= DEFAULT_ENCODING_UPPERCASE ?>" />
    <link rel="shortcut icon" href="/favicon.ico?00001" type="image/x-icon">
    <link href="<?= APP_TEMPLATES ?>css/uikit.css?00001" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/chosen/chosen.css?00004" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/main.css?00004" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/pagination.css?00004" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/nav.css?00001" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/form-password.min.css?00001" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/font-awesome.min.css?00001" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/app_messages.css?00003" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/form-file.css?00001" rel="stylesheet">

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
            <?php
            if (!is_bool(USER_ID)):

            ?>
                <!-- <nav class="uk-navbar">
                    <div class="uk-navbar-content">
                        <ul class="uk-navbar-nav uk-hidden-small">
                            <li data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
                                <a href="#" ><i class="uk-icon-home"></i> Home <i class="uk-icon-caret-down"></i></a>
                                <div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 27px; left: 0px;">
                                    <ul class="uk-nav uk-nav-dropdown">
                                        <li><a href="#">Пункт</a></li>
                                        <li><a href="#">Еще один пункт</a></li>
                                        <li class="uk-nav-header">Заголовок</li>
                                        <li><a href="#">Пункт</a></li>
                                        <li><a href="#">Еще один пункт</a></li>
                                        <li class="uk-nav-divider"></li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="">
                                    <i class="uk-icon-gear"></i>Settigns
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="uk-navbar-flip">
                        <ul class="uk-navbar-nav uk-hidden-small">
                            <li>
                                <a href="/main/logout">
                                    <i class="uk-icon-sign-out"></i>Выход
                                </a>
                            </li>
                        </ul>
                    </div>

                    <a href="#my-id" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>

                </nav> -->

                <nav class="uk-navbar">
                    <div class="uk-navbar-content" style="padding-left: 0;">
                        <?= parent::getMenuPanel() ?>
                    </div>


                    <div class="uk-navbar-flip">
                        <!-- <ul class="uk-navbar-nav uk-hidden-small"> -->
                        <ul class="uk-navbar-nav">
                            <li>
                                <a href="/main/logout">
                                    <i class="uk-icon-sign-out"></i>Выход
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- <a href="#my-id" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a> -->

                </nav>
                <!-- <div id="my-id" class="uk-offcanvas">
                    <div class="uk-offcanvas-bar uk-contrast">...</div>
                </div> -->
            <?php
            endif; //if (isset($user_id) && !is_bool($user_id)):
            ?>
        </td>
    </tr>
    <tr id="content">
        <td style="vertical-align: text-top">
            <div data-uk-grid class="uk-grid uk-grid-collapse">
                <div class="uk-width-1-1 uk-margin-large-bottom" align="center">