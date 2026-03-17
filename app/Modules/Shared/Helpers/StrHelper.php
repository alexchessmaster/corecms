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
    // ── Invisible / zero-width characters (all languages) ───────────────────
    private const INVISIBLE_CHARS = [
        "\u{200B}" => '',
        "&#x200B;" => '',
        "&ZeroWidthSpace;" => '',   // Zero-width space
        "\u{200D}" => '',
        "&#x200D;" => '',                               // Zero-width joiner
        "\u{FEFF}" => '',
        "&#xFEFF;" => '',                               // BOM / zero-width no-break space
        "\u{00AD}" => '',
        "&#x00AD;" => '',
        "&shy;" => '',              // Soft hyphen
        "\u{2060}" => '',
        "&#x2060;" => '',
        "&NoBreak;" => '',          // Word joiner
        "\u{2061}" => '',
        "&#x2061;" => '',
        "&ApplyFunction;" => '',    // Function application (invisible)
        "\u{2062}" => '',
        "&#x2062;" => '',
        "&InvisibleTimes;" => '',   // Invisible times
        "\u{2063}" => '',
        "&#x2063;" => '',
        "&InvisibleComma;" => '',   // Invisible separator
        "\u{2064}" => '',
        "&#x2064;" => '',                               // Invisible plus
        "\u{180E}" => '',
        "&#x180E;" => '',                               // Mongolian vowel separator
        "\u{034F}" => '',
        "&#x034F;" => '',                               // Combining grapheme joiner
        "\u{061C}" => '',
        "&#x061C;" => '',                               // Arabic letter mark
        "\u{FFF9}" => '',
        "&#xFFF9;" => '',                               // Interlinear annotation anchor
        "\u{FFFA}" => '',
        "&#xFFFA;" => '',                               // Interlinear annotation separator
        "\u{FFFB}" => '',
        "&#xFFFB;" => '',                               // Interlinear annotation terminator
    ];

    // ── Bidi / direction-override characters (all languages) ────────────────
    private const DIRECTION_CHARS = [
        "\u{202A}" => '',
        "&#x202A;" => '',
        "&lrm;" => '',    // Left-to-right embedding
        "\u{202B}" => '',
        "&#x202B;" => '',
        "&rlm;" => '',    // Right-to-left embedding
        "\u{202C}" => '',
        "&#x202C;" => '',                     // Pop directional formatting
        "\u{202D}" => '',
        "&#x202D;" => '',                     // Left-to-right override
        "\u{202E}" => '',
        "&#x202E;" => '',                     // Right-to-left override
        "\u{2066}" => '',
        "&#x2066;" => '',                     // Left-to-right isolate
        "\u{2067}" => '',
        "&#x2067;" => '',                     // Right-to-left isolate
        "\u{2068}" => '',
        "&#x2068;" => '',                     // First strong isolate
        "\u{2069}" => '',
        "&#x2069;" => '',                     // Pop directional isolate
    ];

    // ── Cyrillic / Greek homoglyphs (Latin text) ─────────────────────────────
    private const LATIN_HOMOGLYPHS = [
        "\u{0430}" => 'a',
        "&#x0430;" => 'a',   // Cyrillic small a
        "\u{0435}" => 'e',
        "&#x0435;" => 'e',   // Cyrillic small ie
        "\u{043E}" => 'o',
        "&#x043E;" => 'o',   // Cyrillic small o
        "\u{0440}" => 'p',
        "&#x0440;" => 'p',   // Cyrillic small er
        "\u{0441}" => 'c',
        "&#x0441;" => 'c',   // Cyrillic small es
        "\u{0445}" => 'x',
        "&#x0445;" => 'x',   // Cyrillic small ha
        "\u{0443}" => 'y',
        "&#x0443;" => 'y',   // Cyrillic small u
        "\u{0410}" => 'A',
        "&#x0410;" => 'A',   // Cyrillic capital A
        "\u{0412}" => 'B',
        "&#x0412;" => 'B',   // Cyrillic capital Ve
        "\u{0415}" => 'E',
        "&#x0415;" => 'E',   // Cyrillic capital Ie
        "\u{041A}" => 'K',
        "&#x041A;" => 'K',   // Cyrillic capital Ka
        "\u{041C}" => 'M',
        "&#x041C;" => 'M',   // Cyrillic capital Em
        "\u{041D}" => 'H',
        "&#x041D;" => 'H',   // Cyrillic capital En
        "\u{041E}" => 'O',
        "&#x041E;" => 'O',   // Cyrillic capital O
        "\u{0420}" => 'P',
        "&#x0420;" => 'P',   // Cyrillic capital Er
        "\u{0421}" => 'C',
        "&#x0421;" => 'C',   // Cyrillic capital Es
        "\u{0422}" => 'T',
        "&#x0422;" => 'T',   // Cyrillic capital Te
        "\u{0425}" => 'X',
        "&#x0425;" => 'X',   // Cyrillic capital Ha
        "\u{0405}" => 'S',
        "&#x0405;" => 'S',   // Cyrillic capital Dze
        "\u{0406}" => 'I',
        "&#x0406;" => 'I',   // Cyrillic capital I
        "\u{0408}" => 'J',
        "&#x0408;" => 'J',   // Cyrillic capital Je
        "\u{03BF}" => 'o',
        "&#x03BF;" => 'o',   // Greek small omicron
        "\u{039F}" => 'O',
        "&#x039F;" => 'O',   // Greek capital omicron
        "\u{0391}" => 'A',
        "&#x0391;" => 'A',   // Greek capital alpha
        "\u{0392}" => 'B',
        "&#x0392;" => 'B',   // Greek capital beta
        "\u{0395}" => 'E',
        "&#x0395;" => 'E',   // Greek capital epsilon
        "\u{0396}" => 'Z',
        "&#x0396;" => 'Z',   // Greek capital zeta
        "\u{0397}" => 'H',
        "&#x0397;" => 'H',   // Greek capital eta
        "\u{0399}" => 'I',
        "&#x0399;" => 'I',   // Greek capital iota
        "\u{039A}" => 'K',
        "&#x039A;" => 'K',   // Greek capital kappa
        "\u{039C}" => 'M',
        "&#x039C;" => 'M',   // Greek capital mu
        "\u{039D}" => 'N',
        "&#x039D;" => 'N',   // Greek capital nu
        "\u{03A1}" => 'P',
        "&#x03A1;" => 'P',   // Greek capital rho
        "\u{03A4}" => 'T',
        "&#x03A4;" => 'T',   // Greek capital tau
        "\u{03A5}" => 'Y',
        "&#x03A5;" => 'Y',   // Greek capital upsilon
        "\u{03A7}" => 'X',
        "&#x03A7;" => 'X',   // Greek capital chi
    ];

    // ── Typographic substitutes (Latin + Danish) ─────────────────────────────
    private const TYPOGRAPHIC_FIXES = [
        // Curly single quotes & apostrophes
        "\u{2018}" => "'",
        "&#x2018;" => "'",
        "&lsquo;" => "'",   // ' Left single quotation mark
        "\u{2019}" => "'",
        "&#x2019;" => "'",
        "&rsquo;" => "'",   // ' Right single quotation mark
        "\u{201A}" => "'",
        "&#x201A;" => "'",
        "&sbquo;" => "'",   // ‚ Low-9 single
        "\u{201B}" => "'",
        "&#x201B;" => "'",                       // ‛ High-reversed-9 single
        "\u{2039}" => "'",
        "&#x2039;" => "'",
        "&lsaquo;" => "'",  // ‹ Single left angle
        "\u{203A}" => "'",
        "&#x203A;" => "'",
        "&rsaquo;" => "'",  // › Single right angle
        "\u{0060}" => "'",
        "&#x0060;" => "'",                       // ` Grave accent
        "\u{00B4}" => "'",
        "&#x00B4;" => "'",
        "&acute;" => "'",   // ´ Acute accent

        // Curly double quotes
        "\u{201C}" => '"',
        "&#x201C;" => '"',
        "&ldquo;" => '"',   // " Left double quotation mark
        "\u{201D}" => '"',
        "&#x201D;" => '"',
        "&rdquo;" => '"',   // " Right double quotation mark
        "\u{201E}" => '"',
        "&#x201E;" => '"',
        "&bdquo;" => '"',   // „ Low-9 double
        "\u{201F}" => '"',
        "&#x201F;" => '"',                       // ‟ High-reversed-9 double
        "\u{00AB}" => '"',
        "&#x00AB;" => '"',
        "&laquo;" => '"',   // « Left angle quotation mark
        "\u{00BB}" => '"',
        "&#x00BB;" => '"',
        "&raquo;" => '"',   // » Right angle quotation mark

        // Dashes
        "\u{2013}" => '-',
        "&#x2013;" => '-',
        "&ndash;" => '-',   // En dash
        "\u{2014}" => '-',
        "&#x2014;" => '-',
        "&mdash;" => '-',   // Em dash
        "\u{2015}" => '-',
        "&#x2015;" => '-',                       // Horizontal bar
        "\u{2012}" => '-',
        "&#x2012;" => '-',                       // Figure dash
        "\u{2010}" => '-',
        "&#x2010;" => '-',
        "&hyphen;" => '-',  // Hyphen
        "\u{2011}" => '-',
        "&#x2011;" => '-',                       // Non-breaking hyphen
        "\u{FE58}" => '-',
        "&#xFE58;" => '-',                       // Small em dash
        "\u{FE63}" => '-',
        "&#xFE63;" => '-',                       // Small hyphen-minus
        "–"        => '-',                                             // Literal en dash
        "\u{FF0D}" => '-',
        "&#xFF0D;" => '-',                       // Fullwidth hyphen-minus
        "\u{2212}" => '-',
        "&#x2212;" => '-',
        "&minus;" => '-',   // Minus sign
        "\u{2E3A}" => '-',
        "&#x2E3A;" => '-',                       // Two-em dash
        "\u{2E3B}" => '-',
        "&#x2E3B;" => '-',                       // Three-em dash

        // Spaces
        "\u{00A0}" => ' ',
        "&#x00A0;" => ' ',
        "&nbsp;" => ' ',    // Non-breaking space
        "\u{202F}" => ' ',
        "&#x202F;" => ' ',                       // Narrow no-break space
        "\u{2009}" => ' ',
        "&#x2009;" => ' ',
        "&thinsp;" => ' ',  // Thin space
        "\u{200A}" => ' ',
        "&#x200A;" => ' ',
        "&hairsp;" => ' ',  // Hair space
        "\u{3000}" => ' ',
        "&#x3000;" => ' ',                       // Ideographic space
        "\u{2002}" => ' ',
        "&#x2002;" => ' ',
        "&ensp;" => ' ',    // En space
        "\u{2003}" => ' ',
        "&#x2003;" => ' ',
        "&emsp;" => ' ',    // Em space
        "\u{2007}" => ' ',
        "&#x2007;" => ' ',                       // Figure space

        // Other
        "\u{2026}" => '...',
        "&#x2026;" => '...',
        "&hellip;" => '...',  // Ellipsis
        "\u{00B7}" => '.',
        "&#x00B7;" => '.',
        "&middot;" => '.',    // Middle dot
        "\u{0640}" => '',                                                   // Arabic tatweel
    ];

    // ── Persian: Arabic lookalikes → correct Persian characters ─────────────
    private const PERSIAN_FIXES = [
        // Letters
        "\u{0643}" => "\u{06A9}",
        "&#x0643;" => "\u{06A9}",   // Arabic Kaf ك → Persian Keh ک
        "\u{064A}" => "\u{06CC}",
        "&#x064A;" => "\u{06CC}",   // Arabic Yeh ي → Farsi Yeh ی
        "\u{0649}" => "\u{06CC}",
        "&#x0649;" => "\u{06CC}",   // Alef Maksura ى → Farsi Yeh ی
        "\u{06BE}" => "\u{0647}",
        "&#x06BE;" => "\u{0647}",   // Urdu Heh ھ → Persian Heh ه
        "\u{06C0}" => "\u{0647}\u{0654}",
        "&#x06C0;" => "\u{0647}\u{0654}",   // Deprecated Heh+Yeh above → Heh + hamza

        // Arabic-Indic digits → Persian/Extended digits
        "\u{0660}" => "\u{06F0}",
        "&#x0660;" => "\u{06F0}",   // ٠ → ۰
        "\u{0661}" => "\u{06F1}",
        "&#x0661;" => "\u{06F1}",   // ١ → ۱
        "\u{0662}" => "\u{06F2}",
        "&#x0662;" => "\u{06F2}",   // ٢ → ۲
        "\u{0663}" => "\u{06F3}",
        "&#x0663;" => "\u{06F3}",   // ٣ → ۳
        "\u{0664}" => "\u{06F4}",
        "&#x0664;" => "\u{06F4}",   // ٤ → ۴
        "\u{0665}" => "\u{06F5}",
        "&#x0665;" => "\u{06F5}",   // ٥ → ۵
        "\u{0666}" => "\u{06F6}",
        "&#x0666;" => "\u{06F6}",   // ٦ → ۶
        "\u{0667}" => "\u{06F7}",
        "&#x0667;" => "\u{06F7}",   // ٧ → ۷
        "\u{0668}" => "\u{06F8}",
        "&#x0668;" => "\u{06F8}",   // ٨ → ۸
        "\u{0669}" => "\u{06F9}",
        "&#x0669;" => "\u{06F9}",   // ٩ → ۹

        // Punctuation / formatting
        "\u{0640}" => '',
        "&#x0640;" => '',             // Tatweel/Kashida abuse
        "\u{066A}" => '%',
        "&#x066A;" => '%',            // Arabic percent sign
        "\u{066B}" => '.',
        "&#x066B;" => '.',            // Arabic decimal separator
        "\u{066C}" => ',',
        "&#x066C;" => ',',            // Arabic thousands separator
        "\u{FB50}" => "\u{0671}",
        "&#xFB50;" => "\u{0671}",   // Alef Wasla presentation form

        // Quotes → Persian guillemets
        "\u{201C}" => "\u{00AB}",
        "&#x201C;" => "\u{00AB}",
        "&ldquo;" => "\u{00AB}",   // " → «
        "\u{201D}" => "\u{00BB}",
        "&#x201D;" => "\u{00BB}",
        "&rdquo;" => "\u{00BB}",   // " → »
    ];

    // ── Danish: decomposed / lookalike fixes ─────────────────────────────────
    private const DANISH_FIXES = [
        // ── å / Å ────────────────────────────────────────────────────────────────
        "\u{0061}\u{030A}" => "\u{00E5}",
        "&#x0061;&#x030A;" => "\u{00E5}",   // a + combining ring → å
        "\u{0041}\u{030A}" => "\u{00C5}",
        "&#x0041;&#x030A;" => "\u{00C5}",   // A + combining ring → Å
        "\u{212B}"         => "\u{00C5}",
        "&#x212B;"         => "\u{00C5}",   // Angstrom sign → Å
        "&aring;"          => "\u{00E5}",                                        // HTML entity → å
        "&Aring;"          => "\u{00C5}",                                        // HTML entity → Å

        // ── æ / Æ ────────────────────────────────────────────────────────────────
        "\u{0061}\u{0065}" => "\u{00E6}",   // ae ligature lookalike → æ
        "\u{0041}\u{0045}" => "\u{00C6}",   // AE ligature lookalike → Æ
        "\u{00E6}"         => "\u{00E6}",
        "&#x00E6;" => "\u{00E6}",
        "&aelig;" => "\u{00E6}",   // æ normalized
        "\u{00C6}"         => "\u{00C6}",
        "&#x00C6;" => "\u{00C6}",
        "&AElig;" => "\u{00C6}",   // Æ normalized

        // ── ø / Ø ────────────────────────────────────────────────────────────────
        "\u{006F}\u{0338}" => "\u{00F8}",
        "&#x006F;&#x0338;" => "\u{00F8}",   // o + combining solidus → ø
        "\u{004F}\u{0338}" => "\u{00D8}",
        "&#x004F;&#x0338;" => "\u{00D8}",   // O + combining solidus → Ø
        "\u{00F8}"         => "\u{00F8}",
        "&#x00F8;" => "\u{00F8}",
        "&oslash;" => "\u{00F8}",  // ø normalized
        "\u{00D8}"         => "\u{00D8}",
        "&#x00D8;" => "\u{00D8}",
        "&Oslash;" => "\u{00D8}",  // Ø normalized
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
        if (empty($text)) {
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

        \Log::info('$text:' . json_encode($text));


        return $text;
    }
}
