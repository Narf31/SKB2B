<?php

namespace App\Classes\Export\Replacers;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class WordReplacer {

    public static function replace($file_path, $replace_arr) {

        $templateProcessor = new TemplateProcessor($file_path);
        foreach ($replace_arr as $key => $value) {
            if(!is_array($value)){
                $templateProcessor->setValue($key, $value);
            }
        }
        $tempname = md5(microtime());
        $temppath = storage_path() . "/app/public/{$tempname}.docx";
        $templateProcessor->saveAs($temppath);

        $reader = IOFactory::createReader();
        $word = $reader->load($temppath);
        unlink($temppath);

        return $word;
    }

    public static function saveFile(PhpWord $word, $path = '')
    {

        $name = date('Ymd-His')."-".uniqid();
        $xmlWriter = IOFactory::createWriter($word, 'Word2007');
        $xmlWriter->save("{$path}{$name}.docx");

        return $name;
    }

    public static function output(PhpWord $word, $name = false) {

        $word->save('', 'Word2007', true);

    }


    public static function outputPDF(PhpWord $word, $name = false) {

        $date = date('Ymd_His', time());
        $name = $name ? $name : "{$date}.pdf";

        $tempname = md5(microtime());
        $temppath = storage_path() . "/app/public/{$tempname}.docx";
        $newtemppath = storage_path() . "/app/public/{$tempname}.pdf";


        $xmlWriter = IOFactory::createWriter($word, 'Word2007');
        $xmlWriter->save($temppath);


        exec("doc2pdf {$temppath} {$newtemppath}");

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$name.'"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($newtemppath));

        dd($newtemppath);

        ob_clean();
        flush();

        readfile($newtemppath);

        unlink($newtemppath);
        unlink($temppath);

        exit();





    }

}
