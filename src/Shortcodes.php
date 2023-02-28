<?php
namespace UYGDDI;

use UYGDDI\Config;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

class Shortcodes
{
    public function __construct()
    {
    }

    public static function index()
    {
        $config= new Config;
        $filter=$config->shortcodes;
        // add_shortcode('example', array('Shortcodes','example_function'));
        if ($filter) {
            foreach ($filter as $data) {
                call_user_func_array('add_shortcode', [$data[0],$data[1]]);
            }
        }
    }
    /*
    * shortcode example
    * @info: https://codex.wordpress.org/Shortcode
    * @return string
    */
    public static function qrCode($atts)
    {
        extract(shortcode_atts(array(
            'text' => '',
            'size' => '300',
            'margin' => '10',
            'error_correction' => 'high'
            ), $atts));
        if (!$atts['text']) {
            return false;
        }
        $writer = new PngWriter();
        $size = isset($atts['size'])?$atts['size']:300;
        $margin = isset($atts['margin'])?$atts['margin']:10;
        // Create QR code
        $qrCode = QrCode::create($atts['text'])
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize($size)
            ->setMargin($margin)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);
        $dataUri = $result->getDataUri();
        return '<img src="'.$dataUri.'" alt="'.$atts['text'].'">';
    }
}
