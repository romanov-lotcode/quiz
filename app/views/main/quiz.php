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
    <link href="<?= APP_TEMPLATES ?>css/progress.css?00003" rel="stylesheet">

    <script src="<?= APP_TEMPLATES ?>js/nav.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/offcanvas.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/jquery-3.2.1.min.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/jquery.countdown.min.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/modal.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/uikit.js"></script>

    <title>Векторина</title>
</head>
<body onload="changeButtonState();">
<table class="body" cellpadding="0" cellspacing="0" align="center" >
    <tr id="header">
        <td>

        </td>
    </tr>
    <tr id="content">
        <td style="vertical-align: text-top;">
            <div data-uk-grid class="uk-grid uk-grid-collapse">
                <div class="uk-width-1-1 uk-margin-large-bottom" align="center">

                    <div class="uk-width-8-10" id="q_container" align="left" >
                        <?php
                        if ($errors['question'] == null):
                        ?>
                        <form method="POST" class="uk-form">
                            <button class="uk-button complete fr" name="complete" id="complete" title="Завершить тестирование">
                                <span class="uk-form-icon uk-icon-power-off"></span>
                                Завершить
                            </button>
                            <table cellpadding="0" cellspacing="0" class="content">
                                <tr>
                                    <td class="left">
                                        <div class="left">
                                            <table class="sub_content sub_content_left">
                                                <tr class="header">
                                                    <td>
                                                        <div class="container">
                                                            <div class="question"><?= $testing['name']; ?></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="content"
                                                    <?php if (!$answer_allow): ?>
                                                    style="pointer-events: none; background: rgba(0, 0, 0, .3);"
                                                    <?php endif; //if (!$answer_allow): ?>
                                                    >
                                                    <td>
                                                        <div class="container">
                                                            <div class="question"><?= $question_number . '. '. $question['name']; ?></div>

                                                            <?php
                                                            if ($question['path_img'] != null):
                                                            ?>
                                                                <div align="center">
                                                                    <img src="<?= $img_src ?>" alt="Изображение отсутствует" class="quiz_img" />
                                                                </div>
                                                            <?php
                                                            endif; //if ($question['path_img'] != null):
                                                            ?>

                                                            <div class="" align="center">
                                                                <?php
                                                                if ($question['question_time_flag'] == FLAG_ON ):
                                                                    ?>
                                                                <div class="uk-comment-meta" align="center">
                                                                    Данный вопрос имеет ограничение по времени
                                                                </div>

                                                                    <div class="question_time" id="question_time"></div>
                                                                    <script type="text/javascript">
                                                                        $("#question_time")
                                                                            .countdown("<?= $question_countdown ?>", function(event) {
                                                                                $(this).text(
                                                                                    event.strftime('%H:%M:%S')
                                                                                );
                                                                            });
                                                                    </script>
                                                                    <?php
                                                                endif; // if ($question['question_time_flag'] == FLAG_ON ):
                                                                ?>
                                                            </div>

                                                            <hr class="question_separator" />
                                                            <div class="uk-comment-meta" align="center">
                                                                <?php
                                                                if ($question['question_type_id'] == QUESTION_TYPE_ONE_TO_ONE) echo 'Можете выбрать один из вариантов ответа';
                                                                if ($question['question_type_id'] == QUESTION_TYPE_ONE_TO_MANY) echo 'Можете выбрать несколько вариантов ответа';
                                                                ?>
                                                            </div>
                                                            <div class="uk-form-controls" style="margin-top: 10px;">
                                                                <?php
                                                                $n = 0;
                                                                while ($n < count($html_element))
                                                                {
                                                                    echo '<div class="answer">'.$html_element['answer_'.$n]->render().'</div>';
                                                                    $n++;
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="footer">
                                                    <td align="right">
                                                        <?php
                                                        if ($question_number > 1):
                                                        ?>
                                                            <button class="uk-button q_b fl" name="previous">Предыдущий</button>
                                                        <?php
                                                        endif; //if ($question_number > 1):
                                                        //if ($question_number != $question_count):
                                                        ?>
                                                        <button class="uk-button q_b skip" name="skip" id="skip">Пропустить</button>
                                                        <?php
                                                        //endif; //if ($question_number != $question_count):
                                                        ?>
                                                        <button class="uk-button q_b answer" name="respond" id="respond">Ответить</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>

                                    <td class="right uk-contrast">
                                        <div class="right">
                                            <table class="sub_content sub_content_right">
                                                <tr class="header">
                                                    <td>
                                                        <div class="container" align="right">
                                                            <?php
                                                            if ($testing['testing_time_flag'] == FLAG_ON ):
                                                                ?>
                                                                <div class="testing_time" id="testing_time"></div>
                                                                <script type="text/javascript">
                                                                    $("#testing_time")
                                                                        .countdown("<?= $testing_countdown ?>", function(event) {
                                                                            $(this).text(
                                                                                event.strftime('<?php if ($datetime_is_day_view) echo '%D дней '; ?>%H:%M:%S')
                                                                            );
                                                                        });

                                                                    var all_testing_time = <?= $all_testing_time ?>;
                                                                    $(document).ready(function () {
                                                                        setInterval(function () {
                                                                            all_testing_time = all_testing_time - 1;
                                                                            if (all_testing_time == 0) {
                                                                                document.getElementById('complete').click();
                                                                            }
                                                                        }, 1000);
                                                                    });
                                                                </script>
                                                                <?php
                                                            endif; // if ($testing['testing_time_flag'] == FLAG_ON):
                                                            ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="content">
                                                    <td>
                                                        <div class="container" align="center">
                                                            <?php
                                                            if (is_array($questions) && count($questions) > 0):
                                                            ?>
                                                                <ul class="question_number">

                                                                <?php
                                                                $q_numb = 0;
                                                                $is_local_q_answered = false;
                                                                foreach ($questions as $q_item):
                                                                    $q_numb++;
                                                                ?>
                                                                    <li>
                                                                        <?php
                                                                        $temp_key = array_search($q_numb, $answered_question_numbers);

                                                                        if (is_int($temp_key)):
                                                                        ?>
                                                                            <a href="/main/quiz?qid=<?= $q_item ?>" class="answered <?php if ($q_numb == $question_number) echo ' answered_active' ?>">
                                                                                <?= $q_numb; ?>
                                                                            </a>
                                                                        <?php
                                                                        else: // if (is_int($temp_key)):
                                                                        ?>
                                                                            <a href="/main/quiz?qid=<?= $q_item ?>" class="q_n <?php if ($q_numb == $question_number) echo ' active' ?>">
                                                                                <?= $q_numb; ?>
                                                                            </a>
                                                                        <?php
                                                                        endif; //if (is_int($temp_key)):
                                                                        ?>
                                                                    </li>
                                                                <?php
                                                                endforeach; // foreach ($questions as $q_item):
                                                                ?>
                                                                </ul>
                                                            <?php
                                                            endif; //if (is_array($questions) && count($questions) > 0):
                                                            ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="footer">
                                                    <td style="vertical-align: bottom">
                                                        <div class="container">
                                                            Пройдено: <?= $progress_percentagle ?>%
                                                            <div class="uk-progress">
                                                                <div class="uk-progress-bar" style="width: <?= $progress_percentagle ?>%;"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <a href="#quiz_modal" data-uk-modal style="" class="show_modal" id="show_modal">Открыть</a>

                            <div id="quiz_modal" class="uk-modal">

                                <div class="uk-modal-dialog">
                                    <a href="" class="uk-modal-close uk-close uk-close-alt" style="position:relative; top: -20px; right: -20px;"></a>
                                    <?= $modal_message ?>
                                    <?php
                                    if (1 > 2):
                                    ?>

                                        <button class="uk-button complete fr" name="complete" id="complete" title="Завершить тестирование">
                                            <span class="uk-form-icon uk-icon-power-off"></span>
                                            Завершить
                                        </button>
                                    <?php
                                    endif; //if ($is_testing_complete):
                                    ?>
                                </div>
                            </div>
                        </form>
                        <?php
                        endif; //if ($errors['question'] == null):
                        ?>
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
<script>
    function changeButtonState() {
        var b_respond = document.getElementById('respond');
        var b_skip = document.getElementById('skip');
        var is_question_answered = <?php echo ($is_question_answered)? 1: 0; ?>;
        var progress = <?php echo ($is_testing_complete)? 100: 0; ?>;


        if (document.querySelectorAll(":checked").length || is_question_answered == 1)
        {
            b_respond.style.display = 'inline-block';
            b_skip.style.display = 'none';
        }
        else
        {
            b_respond.style.display = 'none';
            b_skip.style.display = 'inline-block';
        }

        if (progress == 100)
        {
            document.getElementById('show_modal').click();
        }
        /*b_respond.style.display = document.querySelectorAll(":checked").length ? 'inline-block': 'none';
        b_skip.style.display = document.querySelectorAll(":checked").length ? 'none': 'inline-block';*/
    }
</script>
</body>
</html>