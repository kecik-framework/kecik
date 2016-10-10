<?php
/**
 * Created by PhpStorm.
 * User: DWIsprananda
 * Date: 9/15/2016
 * Time: 2:42 PM
 */

namespace Kecik;


class Template
{
    private static $instance = null;
    
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
    }

    public static function render($file, $response = '')
    {
        ob_start();
        include Config::get('path.template') . '/' . $file . '.php';
        $fullRender = ob_get_clean();

        $fullRender = preg_replace_callback(
            [
                '/(\\\)?' . addslashes(Config::get('template.open_tag')) . '=?' . '/',
                '/(\\\)?' . addslashes(Config::get('template.close_tag')) . '/'
            ],
            function ($s) {

                if ( isset( $s[0] ) ) {

                    if ( isset( $s[1] ) && $s[1] == '\\' ) {
                        return substr($s[0], 1);
                    } elseif ( $s[0] == Config::get('template.open_tag') ) {
                        return '<?php ';
                    } elseif ( $s[0] == '{{=' ) {
                        return '<?php echo ';
                    } elseif ( $s[0] == Config::get('template.close_tag') ) {
                        return '?>';
                    }

                }
            },
            $fullRender
        );

        $fullRender = str_replace(
            [ '@js', '@css' ],
            [ Assets::$js->render(), Assets::$css->render() ],
            $fullRender
        );

        if ( ! empty( $response ) ) {

            $fullRender = str_replace(
                [ '@yield', '@response' ],
                [ $response, $response ],
                $fullRender
            );

        }
        //-- END Replace Tag

        ob_start();
        eval( '?>' . $fullRender );
        $fullRender = ob_get_clean();

        return $fullRender;
    }
}

Template::init();