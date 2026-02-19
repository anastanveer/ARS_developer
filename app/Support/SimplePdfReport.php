<?php

namespace App\Support;

class SimplePdfReport
{
    /**
     * @param  array<int, string>  $lines
     */
    public static function buildFromLines(array $lines): string
    {
        $clean = [];
        foreach ($lines as $line) {
            $text = self::normalize((string) $line);
            if ($text === '') {
                $clean[] = ' ';
                continue;
            }

            foreach (self::wrap($text, 92) as $wrapped) {
                $clean[] = $wrapped;
            }
        }

        if ($clean === []) {
            $clean[] = 'No data available.';
        }

        $pages = array_chunk($clean, 46);
        $objects = [];
        $pageRefs = [];

        // 1 => catalog, 2 => pages, 3 => font.
        $objects[3] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";

        $id = 4;
        foreach ($pages as $pageLines) {
            $pageId = $id++;
            $contentId = $id++;

            $pageRefs[] = $pageId . ' 0 R';
            $objects[$pageId] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 3 0 R >> >> /Contents {$contentId} 0 R >>";

            $stream = self::buildPageStream($pageLines);
            $objects[$contentId] = "<< /Length " . strlen($stream) . " >>\nstream\n{$stream}\nendstream";
        }

        $objects[2] = "<< /Type /Pages /Kids [ " . implode(' ', $pageRefs) . " ] /Count " . count($pageRefs) . " >>";
        $objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";

        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $objId => $body) {
            $offsets[$objId] = strlen($pdf);
            $pdf .= $objId . " 0 obj\n" . $body . "\nendobj\n";
        }

        $xref = strlen($pdf);
        $max = max(array_keys($objects));
        $pdf .= "xref\n0 " . ($max + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= $max; $i++) {
            $offset = $offsets[$i] ?? 0;
            $pdf .= sprintf('%010d 00000 n ', $offset) . "\n";
        }

        $pdf .= "trailer\n<< /Size " . ($max + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xref}\n%%EOF";

        return $pdf;
    }

    /**
     * @param  array<int, string>  $lines
     */
    private static function buildPageStream(array $lines): string
    {
        $out = "BT\n/F1 11 Tf\n50 795 Td\n14 TL\n";
        $first = true;

        foreach ($lines as $line) {
            $escaped = self::escapePdfText($line);
            if ($first) {
                $out .= "({$escaped}) Tj\n";
                $first = false;
                continue;
            }
            $out .= "T*\n({$escaped}) Tj\n";
        }

        $out .= "ET";
        return $out;
    }

    private static function escapePdfText(string $text): string
    {
        return str_replace(
            ['\\', '(', ')', "\r", "\n", "\t"],
            ['\\\\', '\\(', '\\)', ' ', ' ', ' '],
            $text
        );
    }

    /**
     * @return array<int, string>
     */
    private static function wrap(string $text, int $maxChars): array
    {
        $wrapped = wordwrap($text, $maxChars, "\n", true);
        return array_map('trim', explode("\n", $wrapped));
    }

    private static function normalize(string $text): string
    {
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($ascii === false) {
            $ascii = $text;
        }
        $ascii = preg_replace('/[^\x20-\x7E]/', ' ', $ascii) ?? $ascii;
        return trim(preg_replace('/\s+/', ' ', $ascii) ?? $ascii);
    }
}

