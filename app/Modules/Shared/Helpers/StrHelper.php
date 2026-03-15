<?php

namespace App\Modules\Shared\Helpers;

use Normalizer;

class StrHelper
{
    // ── Characters to replace with a SPACE ───────────────────────────────────
    // Add any character here that should become a space instead of being removed
    private const REPLACE_WITH_SPACE = [
        "\u{200C}",   // Zero-width non-joiner (ZWNJ) — grammatically required in Persian but should be space in Latin
        "&zwnj;",
        // Add more here as needed:
        // "\u{XXXX}",
    ];

    // ── Invisible / zero-width characters (all languages) ───────────────────
    private const INVISIBLE_CHARS = [
        "\u{200B}" => '',   // Zero-width space
        "\u{200D}" => '',   // Zero-width joiner
        "\u{FEFF}" => '',   // BOM / zero-width no-break space
        "\u{00AD}" => '',   // Soft hyphen
        "\u{2060}" => '',   // Word joiner
        "\u{2061}" => '',   // Function application (invisible)
        "\u{2062}" => '',   // Invisible times
        "\u{2063}" => '',   // Invisible separator
        "\u{2064}" => '',   // Invisible plus
        "\u{180E}" => '',   // Mongolian vowel separator
        "\u{034F}" => '',   // Combining grapheme joiner
        "\u{061C}" => '',   // Arabic letter mark
        "\u{FFF9}" => '',   // Interlinear annotation anchor
        "\u{FFFA}" => '',   // Interlinear annotation separator
        "\u{FFFB}" => '',   // Interlinear annotation terminator
    ];

    // ── Bidi / direction-override characters (all languages) ────────────────
    private const DIRECTION_CHARS = [
        "\u{202A}" => '',   // Left-to-right embedding
        "\u{202B}" => '',   // Right-to-left embedding
        "\u{202C}" => '',   // Pop directional formatting
        "\u{202D}" => '',   // Left-to-right override
        "\u{202E}" => '',   // Right-to-left override
        "\u{2066}" => '',   // Left-to-right isolate
        "\u{2067}" => '',   // Right-to-left isolate
        "\u{2068}" => '',   // First strong isolate
        "\u{2069}" => '',   // Pop directional isolate
    ];

    // ── Cyrillic / Greek homoglyphs (Latin text) ─────────────────────────────
    private const LATIN_HOMOGLYPHS = [
        "\u{0430}" => 'a',  "\u{0435}" => 'e',  "\u{043E}" => 'o',
        "\u{0440}" => 'p',  "\u{0441}" => 'c',  "\u{0445}" => 'x',
        "\u{0443}" => 'y',  "\u{0410}" => 'A',  "\u{0412}" => 'B',
        "\u{0415}" => 'E',  "\u{041A}" => 'K',  "\u{041C}" => 'M',
        "\u{041D}" => 'H',  "\u{041E}" => 'O',  "\u{0420}" => 'P',
        "\u{0421}" => 'C',  "\u{0422}" => 'T',  "\u{0425}" => 'X',
        "\u{0405}" => 'S',  "\u{0406}" => 'I',  "\u{0408}" => 'J',
        "\u{03BF}" => 'o',  "\u{039F}" => 'O',  "\u{0391}" => 'A',
        "\u{0392}" => 'B',  "\u{0395}" => 'E',  "\u{0396}" => 'Z',
        "\u{0397}" => 'H',  "\u{0399}" => 'I',  "\u{039A}" => 'K',
        "\u{039C}" => 'M',  "\u{039D}" => 'N',  "\u{03A1}" => 'P',
        "\u{03A4}" => 'T',  "\u{03A5}" => 'Y',  "\u{03A7}" => 'X',
    ];

    // ── Typographic substitutes (Latin + Danish) ─────────────────────────────
    private const TYPOGRAPHIC_FIXES = [
        "\u{2018}" => "'",    "\u{2019}" => "'",   // Curly single quotes
        "\u{201C}" => '"',    "\u{201D}" => '"',   // Curly double quotes
        "\u{2013}" => '-',    "\u{2014}" => '--',  // En / em dash
        "\u{2026}" => '...',  "\u{00B7}" => '.',   // Ellipsis, middle dot
        "\u{00A0}" => ' ',    "\u{202F}" => ' ',   // Non-breaking spaces
        "\u{2009}" => ' ',    "\u{200A}" => ' ',   // Thin / hair space
        "\u{3000}" => ' ',    "\u{2002}" => ' ',   // Ideographic / en space
        "\u{2003}" => ' ',    "\u{2007}" => ' ',   // Em / figure space
        "\u{0640}" => '',                           // Arabic tatweel (non-Arabic context)
        "\u{00AB}" => '"',    "\u{00BB}" => '"',   // Angle quotation marks
    ];

    // ── Persian: Arabic lookalikes → correct Persian characters ─────────────
    private const PERSIAN_FIXES = [
        // Letters
        "\u{0643}" => "\u{06A9}",   // Arabic Kaf ك → Persian Keh ک
        "\u{064A}" => "\u{06CC}",   // Arabic Yeh ي → Farsi Yeh ی
        "\u{0649}" => "\u{06CC}",   // Alef Maksura ى → Farsi Yeh ی
        "\u{06BE}" => "\u{0647}",   // Urdu Heh ھ → Persian Heh ه
        "\u{06C0}" => "\u{0647}\u{0654}", // Deprecated Heh+Yeh above → Heh + hamza
        // Arabic-Indic digits → Persian/Extended digits
        "\u{0660}" => "\u{06F0}",   "\u{0661}" => "\u{06F1}",
        "\u{0662}" => "\u{06F2}",   "\u{0663}" => "\u{06F3}",
        "\u{0664}" => "\u{06F4}",   "\u{0665}" => "\u{06F5}",
        "\u{0666}" => "\u{06F6}",   "\u{0667}" => "\u{06F7}",
        "\u{0668}" => "\u{06F8}",   "\u{0669}" => "\u{06F9}",
        // Punctuation / formatting
        "\u{0640}" => '',           // Tatweel/Kashida abuse
        "\u{066A}" => '%',          // Arabic percent sign
        "\u{066B}" => '.',          // Arabic decimal separator
        "\u{066C}" => ',',          // Arabic thousands separator
        "\u{FB50}" => "\u{0671}",   // Alef Wasla presentation form
        // Quotes → Persian guillemets
        "\u{201C}" => "\u{00AB}",   // " → «
        "\u{201D}" => "\u{00BB}",   // " → »
    ];

    // ── Danish: decomposed / lookalike fixes ─────────────────────────────────
    private const DANISH_FIXES = [
        "\u{0061}\u{030A}" => "\u{00E5}",  // a + combining ring → å
        "\u{0041}\u{030A}" => "\u{00C5}",  // A + combining ring → Å
        "\u{212B}"         => "\u{00C5}",  // Angstrom sign (Å) → Latin Å (U+00C5)
    ];

    /**
     * Detect the script/language of the text to pick the right fix map.
     */
    private static function detectLanguage(string $text): string
    {
        if (preg_match_all('/[\x{0600}-\x{06FF}]/u', $text) > 3) {
            return 'persian';
        }
        if (preg_match('/[æøåÆØÅ]/u', $text)) {
            return 'danish';
        }
        return 'latin';
    }

    /**
     * Remove and fix suspicious Unicode characters from text.
     * Language is auto-detected (Persian / Danish / Latin).
     */
    public static function removeUnicodeCharacters($text)
    {
        if(empty($text)){
            return $text;
        }
        $lang = self::detectLanguage($text);

        // Apply space replacements first (before other fixes)
        $text = str_replace(self::REPLACE_WITH_SPACE, ' ', $text);

        // Build fix map based on detected language
        $fixes = match ($lang) {
            'persian' => array_merge(
                self::INVISIBLE_CHARS,
                self::DIRECTION_CHARS,
                self::PERSIAN_FIXES,
            ),
            'danish' => array_merge(
                self::INVISIBLE_CHARS,
                self::DIRECTION_CHARS,
                self::LATIN_HOMOGLYPHS,
                self::TYPOGRAPHIC_FIXES,
                self::DANISH_FIXES,
            ),
            default => array_merge(
                self::INVISIBLE_CHARS,
                self::DIRECTION_CHARS,
                self::LATIN_HOMOGLYPHS,
                self::TYPOGRAPHIC_FIXES,
            ),
        };

        // Apply all character replacements
        $text = str_replace(array_keys($fixes), array_values($fixes), $text);

        // NFC normalization — critical for Danish (å/æ/ø) and Persian
        if (class_exists(Normalizer::class)) {
            $text = Normalizer::normalize($text, Normalizer::FORM_C) ?: $text;
        }

        // Collapse multiple spaces (Latin/Danish only — RTL Persian spacing is intentional)
        if ($lang !== 'persian') {
            $text = preg_replace('/[ \t]{2,}/', ' ', $text);
            $text = implode("\n", array_map('trim', explode("\n", $text)));
        }

        return $text;
    }
}
