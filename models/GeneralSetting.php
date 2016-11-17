<?php

namespace anda\cms\models;

use Yii;
use yii\helpers\ArrayHelper;

class GeneralSetting extends \yii\base\Model
{
    const DEFAULT_VALUE = [
        'language' => 'th',
        'timezone' => 'Asia/Bangkok',
        'dateformat' => 'F j, Y',
        'timeformat' => 'g:i a',
        'datetimeformat' => 'medium'
    ];
    public $title;
    public $description;
    public $keywords;
    public $email;
    public $language;
    public $timezone;
    public $dateformat;
    public $timeformat;
    public $datetimeformat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [['title', 'email', 'language', 'timezone', 'dateformat', 'timeformat'], 'required'],
                [['email'], 'email'],
                [['description', 'keywords'], 'safe'],
                ['title', 'default', 'value' => 'Yee Site'],
                ['timezone', 'default', 'value' => self::DEFAULT_VALUE['language']],
                ['timezone', 'default', 'value' => self::DEFAULT_VALUE['timezone']],
                ['dateformat', 'default', 'value' => self::DEFAULT_VALUE['dateformat']],
                ['timeformat', 'default', 'value' => self::DEFAULT_VALUE['timeformat']],
                ['datetimeformat', 'default', 'value' => self::DEFAULT_VALUE['datetimeformat']],
            ]);
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Site Title'),
            'description' => Yii::t('app', 'Site Description'),
            'keywords' => Yii::t('app', 'SEO Keywords'),
            'email' => Yii::t('app', 'Admin Email'),
            'language' => Yii::t('app', 'Language'),
            'timezone' => Yii::t('app', 'Timezone'),
            'dateformat' => Yii::t('app', 'Date Format'),
            'timeformat' => Yii::t('app', 'Time Format'),
            'datetimeformat' => Yii::t('app', 'Date and Time Format'),
        ];
    }

    public static function getDateFormats()
    {
        $timestamp = strtotime(date("Y") . '-01-22');
        return [
            'medium' => Yii::$app->formatter->asDate($timestamp, "medium"),
            'long' => Yii::$app->formatter->asDate($timestamp, "long"),
            'full' => Yii::$app->formatter->asDate($timestamp, "full"),
            'yyyy-MM-dd' => Yii::$app->formatter->asDate($timestamp, "yyyy-MM-dd"),
            'dd/MM/yyyy' => Yii::$app->formatter->asDate($timestamp, "dd/MM/yyyy"),
            'MM/dd/yyyy' => Yii::$app->formatter->asDate($timestamp, "MM/dd/yyyy"),
            'dd.MM.yyyy' => Yii::$app->formatter->asDate($timestamp, "dd.MM.yyyy"),
        ];
    }

    public static function getTimeFormats()
    {
        $timestamp = strtotime('2015-01-01 09:45:59');
        return [
            'h:mm a' => Yii::$app->formatter->asTime($timestamp, "h:mm a"),
            'hh:mm a' => Yii::$app->formatter->asTime($timestamp, "hh:mm a"),
            'HH:mm' => Yii::$app->formatter->asTime($timestamp, "HH:mm").' (24-hour)',
            'H:mm' => Yii::$app->formatter->asTime($timestamp, "H:mm").' (24-hour)',
        ];
    }


    public static function getDateTimeFormats()
    {
        $timestamp = strtotime('2015-01-22 09:45:59');
        return [
            'short' => Yii::$app->formatter->asDateTime($timestamp, "short"),
            'medium' => Yii::$app->formatter->asDateTime($timestamp, "medium"),
            'long' => Yii::$app->formatter->asDateTime($timestamp, "long"),
            'full' => Yii::$app->formatter->asDateTime($timestamp, "full"),
        ];
    }

    public static function getTimezones()
    {
        return [
            "Pacific/Midway" => "(GMT-11:00) Midway Island, Samoa",
            "Etc/GMT+10" => "(GMT-10:00) Hawaii",
            "Pacific/Marquesas" => "(GMT-09:30) Marquesas Islands",
            "America/Anchorage" => "(GMT-09:00) Alaska",
            "America/Los_Angeles" => "(GMT-08:00) Pacific Time (US & Canada)",
            "America/Denver" => "(GMT-07:00) Mountain Time (US & Canada)",
            "America/Chihuahua" => "(GMT-07:00) Chihuahua, La Paz, Mazatlan",
            "America/Dawson_Creek" => "(GMT-07:00) Arizona",
            "America/Belize" => "(GMT-06:00) Saskatchewan, Central America",
            "America/Cancun" => "(GMT-06:00) Guadalajara, Mexico City, Monterrey",
            "Chile/EasterIsland" => "(GMT-06:00) Easter Island",
            "America/Chicago" => "(GMT-06:00) Central Time (US & Canada)",
            "America/New_York" => "(GMT-05:00) Eastern Time (US & Canada)",
            "America/Havana" => "(GMT-05:00) Cuba",
            "America/Bogota" => "(GMT-05:00) Bogota, Lima, Quito, Rio Branco",
            "America/Caracas" => "(GMT-04:30) Caracas",
            "America/Santiago" => "(GMT-04:00) Santiago",
            "America/La_Paz" => "(GMT-04:00) La Paz",
            "America/Campo_Grande" => "(GMT-04:00) Brazil",
            "America/Goose_Bay" => "(GMT-04:00) Atlantic Time (Goose Bay)",
            "America/Glace_Bay" => "(GMT-04:00) Atlantic Time (Canada)",
            "America/St_Johns" => "(GMT-03:30) Newfoundland",
            "America/Araguaina" => "(GMT-03:00) UTC-3",
            "America/Montevideo" => "(GMT-03:00) Montevideo",
            "America/Godthab" => "(GMT-03:00) Greenland",
            "America/Argentina/Buenos_Aires" => "(GMT-03:00) Buenos Aires",
            "America/Sao_Paulo" => "(GMT-03:00) Brasilia",
            "America/Noronha" => "(GMT-02:00) Mid-Atlantic",
            "Atlantic/Cape_Verde" => "(GMT-01:00) Cape Verde Is.",
            "Atlantic/Azores" => "(GMT-01:00) Azores",
            "Europe/London" => "(GMT) Greenwich Mean Time : London",
            "Africa/Abidjan" => "(GMT) Monrovia, Reykjavik",
            "Europe/Amsterdam" => "(GMT+01:00) Western & Central Europe",
            "Africa/Algiers" => "(GMT+01:00) West Central Africa",
            "Africa/Windhoek" => "(GMT+01:00) Windhoek",
            "Africa/Cairo" => "(GMT+02:00) Kiev, Cairo, Pretoria, Jerusalem",
            "Europe/Moscow" => "(GMT+03:00) Nairobi, Moscow",
            "Asia/Tehran" => "(GMT+03:30) Tehran",
            "Asia/Dubai" => "(GMT+04:00) Abu Dhabi, Muscat",
            "Asia/Yerevan" => "(GMT+04:00) Yerevan",
            "Asia/Kabul" => "(GMT+04:30) Kabul",
            "Asia/Tashkent" => "(GMT+05:00) Tashkent",
            "Asia/Kolkata" => "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
            "Asia/Katmandu" => "(GMT+05:45) Kathmandu",
            "Asia/Dhaka" => "(GMT+06:00) Astana, Dhaka",
            "Asia/Novosibirsk" => "(GMT+06:00) Novosibirsk",
            "Asia/Rangoon" => "(GMT+06:30) Yangon (Rangoon)",
            "Asia/Bangkok" => "(GMT+07:00) Bangkok, Hanoi, Jakarta",
            "Asia/Hong_Kong" => "(GMT+08:00) Beijing, Hong Kong",
            "Asia/Irkutsk" => "(GMT+08:00) Irkutsk, Ulaan Bataar",
            "Australia/Eucla" => "(GMT+08:45) Eucla",
            "Asia/Tokyo" => "(GMT+09:00) Osaka, Sapporo, Tokyo",
            "Asia/Seoul" => "(GMT+09:00) Seoul",
            "Australia/Adelaide" => "(GMT+09:30) Adelaide",
            "Australia/Brisbane" => "(GMT+10:00) Brisbane",
            "Australia/Hobart" => "(GMT+10:00) Hobart",
            "Asia/Vladivostok" => "(GMT+10:00) Vladivostok",
            "Australia/Lord_Howe" => "(GMT+10:30) Lord Howe Island",
            "Etc/GMT-11" => "(GMT+11:00) Solomon Is., New Caledonia",
            "Pacific/Norfolk" => "(GMT+11:30) Norfolk Island",
            "Asia/Anadyr" => "(GMT+12:00) Anadyr, Kamchatka",
            "Pacific/Auckland" => "(GMT+12:00) Auckland, Wellington",
            "Etc/GMT-12" => "(GMT+12:00) Fiji, Kamchatka, Marshall Is.",
            "Pacific/Chatham" => "(GMT+12:45) Chatham Islands",
            "Pacific/Tongatapu" => "(GMT+13:00) Nuku'alofa",
            "Pacific/Kiritimati" => "(GMT+14:00) Kiritimati",
        ];
    }

    public static function getLanguages()
    {
        $languages = [
            'om' => '(Afan)/Oromoor/Oriya (om)' ,
            'ab' => 'Abkhazian (ab)' ,
            'aa' => 'Afar (aa)' ,
            'af' => 'Afrikaans (af)' ,
            'sq' => 'Albanian (sq)' ,
            'am' => 'Amharic (am)' ,
            'ar' => 'Arabic (ar)' ,
            'hy' => 'Armenian (hy)' ,
            'as' => 'Assamese (as)' ,
            'ay' => 'Aymara (ay)' ,
            'az' => 'Azerbaijani (az)' ,
            'ba' => 'Bashkir (ba)' ,
            'eu' => 'Basque (eu)' ,
            'bn' => 'Bengali/Bangla (bn)' ,
            'dz' => 'Bhutani (dz)' ,
            'bh' => 'Bihari (bh)' ,
            'bi' => 'Bislama (bi)' ,
            'br' => 'Breton (br)' ,
            'bg' => 'Bulgarian (bg)' ,
            'my' => 'Burmese (my)' ,
            'be' => 'Byelorussian (be)' ,
            'km' => 'Cambodian (km)' ,
            'ca' => 'Catalan (ca)' ,
            'zh' => 'Chinese (zh)' ,
            'co' => 'Corsican (co)' ,
            'hr' => 'Croatian (hr)' ,
            'cs' => 'Czech (cs)' ,
            'da' => 'Danish (da)' ,
            'nl' => 'Dutch (nl)' ,
            'en' => 'English (en)' ,
            'eo' => 'Esperanto (eo)' ,
            'et' => 'Estonian (et)' ,
            'fo' => 'Faeroese (fo)' ,
            'fj' => 'Fiji (fj)' ,
            'fi' => 'Finnish (fi)' ,
            'fr' => 'French (fr)' ,
            'fy' => 'Frisian (fy)' ,
            'gl' => 'Galician (gl)' ,
            'ka' => 'Georgian (ka)' ,
            'de' => 'German (de)' ,
            'el' => 'Greek (el)' ,
            'kl' => 'Greenlandic (kl)' ,
            'gn' => 'Guarani (gn)' ,
            'gu' => 'Gujarati (gu)' ,
            'ha' => 'Hausa (ha)' ,
            'iw' => 'Hebrew (iw)' ,
            'hi' => 'Hindi (hi)' ,
            'hu' => 'Hungarian (hu)' ,
            'is' => 'Icelandic (is)' ,
            'in' => 'Indonesian (in)' ,
            'ia' => 'Interlingua (ia)' ,
            'ie' => 'Interlingue (ie)' ,
            'ik' => 'Inupiak (ik)' ,
            'ga' => 'Irish (ga)' ,
            'it' => 'Italian (it)' ,
            'ja' => 'Japanese (ja)' ,
            'jw' => 'Javanese (jw)' ,
            'kn' => 'Kannada (kn)' ,
            'ks' => 'Kashmiri (ks)' ,
            'kk' => 'Kazakh (kk)' ,
            'rw' => 'Kinyarwanda (rw)' ,
            'ky' => 'Kirghiz (ky)' ,
            'rn' => 'Kirundi (rn)' ,
            'ko' => 'Korean (ko)' ,
            'ku' => 'Kurdish (ku)' ,
            'lo' => 'Laothian (lo)' ,
            'la' => 'Latin (la)' ,
            'lv' => 'Latvian/Lettish (lv)' ,
            'ln' => 'Lingala (ln)' ,
            'lt' => 'Lithuanian (lt)' ,
            'mk' => 'Macedonian (mk)' ,
            'mg' => 'Malagasy (mg)' ,
            'ms' => 'Malay (ms)' ,
            'ml' => 'Malayalam (ml)' ,
            'mt' => 'Maltese (mt)' ,
            'mi' => 'Maori (mi)' ,
            'mr' => 'Marathi (mr)' ,
            'mo' => 'Moldavian (mo)' ,
            'mn' => 'Mongolian (mn)' ,
            'na' => 'Nauru (na)' ,
            'ne' => 'Nepali (ne)' ,
            'no' => 'Norwegian (no)' ,
            'oc' => 'Occitan (oc)' ,
            'ps' => 'Pashto/Pushto (ps)' ,
            'fa' => 'Persian (fa)' ,
            'pl' => 'Polish (pl)' ,
            'pt' => 'Portuguese (pt)' ,
            'pa' => 'Punjabi (pa)' ,
            'qu' => 'Quechua (qu)' ,
            'rm' => 'Rhaeto-Romance (rm)' ,
            'ro' => 'Romanian (ro)' ,
            'ru' => 'Russian (ru)' ,
            'sm' => 'Samoan (sm)' ,
            'sg' => 'Sangro (sg)' ,
            'sa' => 'Sanskrit (sa)' ,
            'gd' => 'Scots/Gaelic (gd)' ,
            'sr' => 'Serbian (sr)' ,
            'sh' => 'Serbo-Croatian (sh)' ,
            'st' => 'Sesotho (st)' ,
            'tn' => 'Setswana (tn)' ,
            'sn' => 'Shona (sn)' ,
            'sd' => 'Sindhi (sd)' ,
            'si' => 'Singhalese (si)' ,
            'ss' => 'Siswati (ss)' ,
            'sk' => 'Slovak (sk)' ,
            'sl' => 'Slovenian (sl)' ,
            'so' => 'Somali (so)' ,
            'es' => 'Spanish (es)' ,
            'su' => 'Sundanese (su)' ,
            'sw' => 'Swahili (sw)' ,
            'sv' => 'Swedish (sv)' ,
            'tl' => 'Tagalog (tl)' ,
            'tg' => 'Tajik (tg)' ,
            'ta' => 'Tamil (ta)' ,
            'tt' => 'Tatar (tt)' ,
            'te' => 'Tegulu (te)' ,
            'th' => 'Thai (th)' ,
            'bo' => 'Tibetan (bo)' ,
            'ti' => 'Tigrinya (ti)' ,
            'to' => 'Tonga (to)' ,
            'ts' => 'Tsonga (ts)' ,
            'tr' => 'Turkish (tr)' ,
            'tk' => 'Turkmen (tk)' ,
            'tw' => 'Twi (tw)' ,
            'uk' => 'Ukrainian (uk)' ,
            'ur' => 'Urdu (ur)' ,
            'uz' => 'Uzbek (uz)' ,
            'vi' => 'Vietnamese (vi)' ,
            'vo' => 'Volapuk (vo)' ,
            'cy' => 'Welsh (cy)' ,
            'wo' => 'Wolof (wo)' ,
            'xh' => 'Xhosa (xh)' ,
            'ji' => 'Yiddish (ji)' ,
            'yo' => 'Yoruba (yo)' ,
            'zu' => 'Zulu (zu)' ,
        ];
        asort($languages);

        return $languages;
    }
}