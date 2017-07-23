<?php


class App_Message
{
    public static function getMessage($message = '', $type = MESSAGE_TYPE_ERROR)
    {
        if ($type == MESSAGE_TYPE_ERROR)
        {
            return '
            <div class="app_message_type_error uk-alert uk-alert-warning" data-uk-alert>
                <a href="" class="uk-alert-close uk-close"></a>
                <p><i class="app_message_type_error uk-icon-exclamation-triangle"></i> '. $message .'</p>
            </div>
            ';
        }
    }
}