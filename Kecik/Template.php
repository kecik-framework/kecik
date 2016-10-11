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
        readfile(Config::get('path.template') . '/' . $file . '.php');
        $fullRender = ob_get_clean();

        $fullRender = self::parse($fullRender, $response);

        $temp = tempnam(sys_get_temp_dir(), 'KET');
        $f = fopen($temp, 'w');
        fwrite($f, $fullRender);
        fclose($f); // this removes the file
        ob_start();
        include( $temp );
        $fullRender = ob_get_clean();

        return $fullRender;

        /*ob_start();
        eval('?> ' . $fullRender);
        $fullRender = ob_get_clean();
        return $fullRender;*/
    }

    /**
     * @param $response
     * @param $fullRender
     *
     * @return mixed
     */
    public static function parse($fullRender, $response = '')
    {
        $open_tags_replaces = [
            '[' => '\\[',
            '{' => '\\{',
            '(' => '\\(',
        ];

        $close_tags_replaces = [
            ']' => '\\]',
            '}' => '\\}',
            ')' => '\\)',
            '?' => '\\?',
            '$' => '\\$',
            '^' => '\\^',
            '*' => '\\*',
            '+' => '\\+',
            '|' => '\\|',
            '.' => '\\.',
            '/' => '\\/',

        ];

        $open_tag = addslashes(Config::get('template.open_tag'));
        $close_tag = addslashes(Config::get('template.close_tag'));

        foreach ( $open_tags_replaces as $search => $open_tags_replace ) {
            $open_tag = str_replace($search, $open_tags_replace, $open_tag);
        }

        foreach ( $close_tags_replaces as $search => $close_tags_replace ) {
            $close_tag = str_replace($search, $close_tags_replace, $close_tag);
        }

        $fullRender = preg_replace_callback(
            [
                '/(\\\)?' . $open_tag . '=?' . '/',
                '/(\\\)?' . $close_tag . '/'
            ],
            function ($s) {

                if ( isset( $s[0] ) ) {
                    if ( isset( $s[1] ) && $s[1] == '\\' ) {
                        return substr($s[0], 1);
                    } elseif ( $s[0] == Config::get('template.open_tag') ) {
                        return '<?php ';
                    } elseif ( $s[0] == '<[=' ) {
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

            return $fullRender;

        }

        return $fullRender;
        //-- END Replace Tag
    }
}

Template::init();
