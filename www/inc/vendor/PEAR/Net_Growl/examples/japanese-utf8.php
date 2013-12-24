<?php
/**
 * Net_Growl Japanese Test Script (file encoding is UTF-8)
 *
 * @author Takeshi Kawamoto <yuki@transrain.net>
 * @since  version 2.3.0
 * @link   https://pear.php.net/bugs/bug.php?id=18589
 */

//multibyte settings (use mbstring extension)
//ini_set('default_charset', 'UTF-8');
ini_set('mbstring.language', 'Japanese');
//ini_set('mbstring.encoding_translation', 'Off');
//ini_set('mbstring.internal_encoding', 'UTF-8');
//ini_set('mbstring.http_input', 'auto');
//ini_set('mbstring.http_output', 'auto');
//ini_set('mbstring.detect_order', 'auto');
//ini_set('mbstring.substitute_character', 'none');

require_once 'Net/Growl/Autoload.php';

$opt = array(
    'protocol'  => 'gntp',
    'timeout'   => 15,
    'AppIcon'   => dirname(__FILE__) . '/info.png',
    'debug'     => dirname(__FILE__) . DIRECTORY_SEPARATOR .
        basename(__FILE__, '.php') . '.log'
);

// notification multibyte test(asian languages)
$notify = array(
    'ja' => array(
        'display' => 'テスト通知(japanese)',
    ),
    'ch' => array(
        'display' => '测验通报(chinese)',
    ),
    'kr' => array(
        'display' => '테스트 통지(korean)',
    ),
);

// application name multibyte test(asian languages)
$application = 'アプリケーション/应用程序/어플리케이션';
$password    = 'test';


$growl = Net_Growl::singleton($application, $notify, $password, $opt);

// garbage characters application name. / not garbage characters notification type display(GNTP).
$growl->register();

$ja_tit = '日本語タイトル(Japanese title)';
$ja_msg = '日本語メッセージ(Japanese message)';
$ch_tit = '中文大标题(Chinese title)';
$ch_msg = '中文留言(Chinese message)';
$kr_tit = '한국어 타이틀(korean title)';
$kr_msg = '한국어 메세지(Korean message)';

/*
 * UTF-8 input parameter.
 * garbage characters title and messages.
 */
$growl->notify('ja', $ja_tit, $ja_msg);
$growl->notify('ch', $ch_tit, $ch_msg);
$growl->notify('kr', $kr_tit, $kr_msg);
