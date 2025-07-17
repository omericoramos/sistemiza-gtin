<?php

namespace App\Support;

use SimpleXMLElement;

class XmlFileParser
{
    /**
     * Lê e retorna o conteúdo de um arquivo XML tratado, ou null se inválido.
     */
    public static function extract(string $filePath): ?SimpleXMLElement
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if (strtolower($extension) !== 'xml') {
            return null;
        }

        libxml_use_internal_errors(true);

        $fileContents = file_get_contents($filePath);

        if ($fileContents === false) {
            return null;
        }

        $encoding = mb_detect_encoding($fileContents, "UTF-8,ISO-8859-1,WINDOWS-1251");
        $xml = @mb_convert_encoding($fileContents, 'UTF-8', $encoding);

        $parsedXml = @simplexml_load_string($xml);

        if ($parsedXml) {
            return $parsedXml;
        }

        return null;
    }
}
