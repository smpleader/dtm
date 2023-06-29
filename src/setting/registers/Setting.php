<?php
namespace DTM\setting\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Setting
{
    public static function registerItem( IApp $app )
    {
        $options = [
            [
                'text' => 'Default',
                'value' => ''
            ],
            [
                'text' => 'Asia/Kabul',
                'value' => 'Afghanistan'
            ],
            [
                'text' => 'Europe/Tirane',
                'value' => 'Albania'
            ],
            [
                'text' => 'Africa/Algiers',
                'value' => 'Algeria'
            ],
            [
                'text' => 'Pacific/Pago_Pago',
                'value' => 'American Samoa'
            ],
            [
                'text' => 'Europe/Andorra',
                'value' => 'Andorra'
            ],
            [
                'text' => 'Africa/Luanda',
                'value' => 'Angola'
            ],
            [
                'text' => 'America/Anguilla',
                'value' => 'Anguilla'
            ],
            [
                'text' => 'Antarctica/Casey',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/Davis',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/DumontDUrville',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/Mawson',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/McMurdo',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/Palmer',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/Rothera',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/Syowa',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/Troll',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'Antarctica/Vostok',
                'value' => 'Antarctica'
            ],
            [
                'text' => 'America/Antigua',
                'value' => 'Antigua and Barbuda'
            ],
            [
                'text' => 'America/Argentina/Buenos_Aires',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/Catamarca',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/Cordoba',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/Jujuy',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/La_Rioja',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/Mendoza',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/Rio_Gallegos',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/Salta',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/San_Juan',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/San_Luis',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/Tucuman',
                'value' => 'Argentina'
            ],
            [
                'text' => 'America/Argentina/Ushuaia',
                'value' => 'Argentina'
            ],
            [
                'text' => 'Asia/Yerevan',
                'value' => 'Armenia'
            ],
            [
                'text' => 'America/Aruba',
                'value' => 'Aruba'
            ],
            [
                'text' => 'Antarctica/Macquarie',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Adelaide',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Brisbane',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Broken_Hill',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Darwin',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Eucla',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Hobart',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Lindeman',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Lord_Howe',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Melbourne',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Perth',
                'value' => 'Australia'
            ],
            [
                'text' => 'Australia/Sydney',
                'value' => 'Australia'
            ],
            [
                'text' => 'Europe/Vienna',
                'value' => 'Austria'
            ],
            [
                'text' => 'Asia/Baku',
                'value' => 'Azerbaijan'
            ],
            [
                'text' => 'America/Nassau',
                'value' => 'Bahamas'
            ],
            [
                'text' => 'Asia/Bahrain',
                'value' => 'Bahrain'
            ],
            [
                'text' => 'Asia/Dhaka',
                'value' => 'Bangladesh'
            ],
            [
                'text' => 'America/Barbados',
                'value' => 'Barbados'
            ],
            [
                'text' => 'Europe/Minsk',
                'value' => 'Belarus'
            ],
            [
                'text' => 'Europe/Brussels',
                'value' => 'Belgium'
            ],
            [
                'text' => 'America/Belize',
                'value' => 'Belize'
            ],
            [
                'text' => 'Africa/Porto-Novo',
                'value' => 'Benin'
            ],
            [
                'text' => 'Atlantic/Bermuda',
                'value' => 'Bermuda'
            ],
            [
                'text' => 'Asia/Thimphu',
                'value' => 'Bhutan'
            ],
            [
                'text' => 'Plurinational State of',
                'value' => 'Bolivia'
            ],
            [
                'text' => 'Sint Eustatius and Saba',
                'value' => 'Bonaire'
            ],
            [
                'text' => 'Europe/Sarajevo',
                'value' => 'Bosnia and Herzegovina'
            ],
            [
                'text' => 'Africa/Gaborone',
                'value' => 'Botswana'
            ],
            [
                'text' => 'America/Araguaina',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Bahia',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Belem',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Boa_Vista',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Campo_Grande',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Cuiaba',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Eirunepe',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Fortaleza',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Maceio',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Manaus',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Noronha',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Porto_Velho',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Recife',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Rio_Branco',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Santarem',
                'value' => 'Brazil'
            ],
            [
                'text' => 'America/Sao_Paulo',
                'value' => 'Brazil'
            ],
            [
                'text' => 'Indian/Chagos',
                'value' => 'British Indian Ocean Territory'
            ],
            [
                'text' => 'Asia/Brunei',
                'value' => 'Brunei Darussalam'
            ],
            [
                'text' => 'Europe/Sofia',
                'value' => 'Bulgaria'
            ],
            [
                'text' => 'Africa/Ouagadougou',
                'value' => 'Burkina Faso'
            ],
            [
                'text' => 'Africa/Bujumbura',
                'value' => 'Burundi'
            ],
            [
                'text' => 'Asia/Phnom_Penh',
                'value' => 'Cambodia'
            ],
            [
                'text' => 'Africa/Douala',
                'value' => 'Cameroon'
            ],
            [
                'text' => 'America/Atikokan',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Blanc-Sablon',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Cambridge_Bay',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Creston',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Dawson',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Dawson_Creek',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Edmonton',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Fort_Nelson',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Glace_Bay',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Goose_Bay',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Halifax',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Inuvik',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Iqaluit',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Moncton',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Nipigon',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Pangnirtung',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Rainy_River',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Rankin_Inlet',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Regina',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Resolute',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/St_Johns',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Swift_Current',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Thunder_Bay',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Toronto',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Vancouver',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Whitehorse',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Winnipeg',
                'value' => 'Canada'
            ],
            [
                'text' => 'America/Yellowknife',
                'value' => 'Canada'
            ],
            [
                'text' => 'Atlantic/Cape_Verde',
                'value' => 'Cape Verde'
            ],
            [
                'text' => 'America/Cayman',
                'value' => 'Cayman Islands'
            ],
            [
                'text' => 'Africa/Bangui',
                'value' => 'Central African Republic'
            ],
            [
                'text' => 'Africa/Ndjamena',
                'value' => 'Chad'
            ],
            [
                'text' => 'America/Punta_Arenas',
                'value' => 'Chile'
            ],
            [
                'text' => 'America/Santiago',
                'value' => 'Chile'
            ],
            [
                'text' => 'Pacific/Easter',
                'value' => 'Chile'
            ],
            [
                'text' => 'Asia/Shanghai',
                'value' => 'China'
            ],
            [
                'text' => 'Asia/Urumqi',
                'value' => 'China'
            ],
            [
                'text' => 'Indian/Christmas',
                'value' => 'Christmas Island'
            ],
            [
                'text' => 'Indian/Cocos',
                'value' => 'Cocos (Keeling) Islands'
            ],
            [
                'text' => 'America/Bogota',
                'value' => 'Colombia'
            ],
            [
                'text' => 'Indian/Comoro',
                'value' => 'Comoros'
            ],
            [
                'text' => 'Pacific/Rarotonga',
                'value' => 'Cook Islands'
            ],
            [
                'text' => 'America/Costa_Rica',
                'value' => 'Costa Rica'
            ],
            [
                'text' => 'Europe/Zagreb',
                'value' => 'Croatia'
            ],
            [
                'text' => 'America/Havana',
                'value' => 'Cuba'
            ],
            [
                'text' => 'America/Curacao',
                'value' => 'Curaçao'
            ],
            [
                'text' => 'Asia/Famagusta',
                'value' => 'Cyprus'
            ],
            [
                'text' => 'Asia/Nicosia',
                'value' => 'Cyprus'
            ],
            [
                'text' => 'Europe/Prague',
                'value' => 'Czech Republic'
            ],
            [
                'text' => 'Africa/Abidjan',
                'value' => 'Côte d Ivoire'
            ],
            [
                'text' => 'Europe/Copenhagen',
                'value' => 'Denmark'
            ],
            [
                'text' => 'Africa/Djibouti',
                'value' => 'Djibouti'
            ],
            [
                'text' => 'America/Dominica',
                'value' => 'Dominica'
            ],
            [
                'text' => 'America/Santo_Domingo',
                'value' => 'Dominican Republic'
            ],
            [
                'text' => 'America/Guayaquil',
                'value' => 'Ecuador'
            ],
            [
                'text' => 'Pacific/Galapagos',
                'value' => 'Ecuador'
            ],
            [
                'text' => 'Africa/Cairo',
                'value' => 'Egypt'
            ],
            [
                'text' => 'America/El_Salvador',
                'value' => 'El Salvador'
            ],
            [
                'text' => 'Africa/Malabo',
                'value' => 'Equatorial Guinea'
            ],
            [
                'text' => 'Africa/Asmara',
                'value' => 'Eritrea'
            ],
            [
                'text' => 'Europe/Tallinn',
                'value' => 'Estonia'
            ],
            [
                'text' => 'Africa/Addis_Ababa',
                'value' => 'Ethiopia'
            ],
            [
                'text' => 'Atlantic/Stanley',
                'value' => 'Falkland Islands (Malvinas)'
            ],
            [
                'text' => 'Atlantic/Faroe',
                'value' => 'Faroe Islands'
            ],
            [
                'text' => 'Pacific/Fiji',
                'value' => 'Fiji'
            ],
            [
                'text' => 'Europe/Helsinki',
                'value' => 'Finland'
            ],
            [
                'text' => 'Europe/Paris',
                'value' => 'France'
            ],
            [
                'text' => 'America/Cayenne',
                'value' => 'French Guiana'
            ],
            [
                'text' => 'Pacific/Gambier',
                'value' => 'French Polynesia'
            ],
            [
                'text' => 'Pacific/Marquesas',
                'value' => 'French Polynesia'
            ],
            [
                'text' => 'Pacific/Tahiti',
                'value' => 'French Polynesia'
            ],
            [
                'text' => 'Indian/Kerguelen',
                'value' => 'French Southern Territories'
            ],
            [
                'text' => 'Africa/Libreville',
                'value' => 'Gabon'
            ],
            [
                'text' => 'Africa/Banjul',
                'value' => 'Gambia'
            ],
            [
                'text' => 'Asia/Tbilisi',
                'value' => 'Georgia'
            ],
            [
                'text' => 'Europe/Berlin',
                'value' => 'Germany'
            ],
            [
                'text' => 'Europe/Busingen',
                'value' => 'Germany'
            ],
            [
                'text' => 'Africa/Accra',
                'value' => 'Ghana'
            ],
            [
                'text' => 'Europe/Gibraltar',
                'value' => 'Gibraltar'
            ],
            [
                'text' => 'Europe/Athens',
                'value' => 'Greece'
            ],
            [
                'text' => 'America/Danmarkshavn',
                'value' => 'Greenland'
            ],
            [
                'text' => 'America/Nuuk',
                'value' => 'Greenland'
            ],
            [
                'text' => 'America/Scoresbysund',
                'value' => 'Greenland'
            ],
            [
                'text' => 'America/Thule',
                'value' => 'Greenland'
            ],
            [
                'text' => 'America/Grenada',
                'value' => 'Grenada'
            ],
            [
                'text' => 'America/Guadeloupe',
                'value' => 'Guadeloupe'
            ],
            [
                'text' => 'Pacific/Guam',
                'value' => 'Guam'
            ],
            [
                'text' => 'America/Guatemala',
                'value' => 'Guatemala'
            ],
            [
                'text' => 'Europe/Guernsey',
                'value' => 'Guernsey'
            ],
            [
                'text' => 'Africa/Conakry',
                'value' => 'Guinea'
            ],
            [
                'text' => 'Africa/Bissau',
                'value' => 'Guinea-Bissau'
            ],
            [
                'text' => 'America/Guyana',
                'value' => 'Guyana'
            ],
            [
                'text' => 'America/Port-au-Prince',
                'value' => 'Haiti'
            ],
            [
                'text' => 'Europe/Vatican',
                'value' => 'Holy See (Vatican City State)'
            ],
            [
                'text' => 'America/Tegucigalpa',
                'value' => 'Honduras'
            ],
            [
                'text' => 'Asia/Hong_Kong',
                'value' => 'Hong Kong'
            ],
            [
                'text' => 'Europe/Budapest',
                'value' => 'Hungary'
            ],
            [
                'text' => 'Atlantic/Reykjavik',
                'value' => 'Iceland'
            ],
            [
                'text' => 'Asia/Kolkata',
                'value' => 'India'
            ],
            [
                'text' => 'Asia/Jakarta',
                'value' => 'Indonesia'
            ],
            [
                'text' => 'Asia/Jayapura',
                'value' => 'Indonesia'
            ],
            [
                'text' => 'Asia/Makassar',
                'value' => 'Indonesia'
            ],
            [
                'text' => 'Asia/Pontianak',
                'value' => 'Indonesia'
            ],
            [
                'text' => 'Islamic Republic of',
                'value' => 'Iran'
            ],
            [
                'text' => 'Asia/Baghdad',
                'value' => 'Iraq'
            ],
            [
                'text' => 'Europe/Dublin',
                'value' => 'Ireland'
            ],
            [
                'text' => 'Europe/Isle_of_Man',
                'value' => 'Isle of Man'
            ],
            [
                'text' => 'Asia/Jerusalem',
                'value' => 'Israel'
            ],
            [
                'text' => 'Europe/Rome',
                'value' => 'Italy'
            ],
            [
                'text' => 'America/Jamaica',
                'value' => 'Jamaica'
            ],
            [
                'text' => 'Asia/Tokyo',
                'value' => 'Japan'
            ],
            [
                'text' => 'Europe/Jersey',
                'value' => 'Jersey'
            ],
            [
                'text' => 'Asia/Amman',
                'value' => 'Jordan'
            ],
            [
                'text' => 'Asia/Almaty',
                'value' => 'Kazakhstan'
            ],
            [
                'text' => 'Asia/Aqtau',
                'value' => 'Kazakhstan'
            ],
            [
                'text' => 'Asia/Aqtobe',
                'value' => 'Kazakhstan'
            ],
            [
                'text' => 'Asia/Atyrau',
                'value' => 'Kazakhstan'
            ],
            [
                'text' => 'Asia/Oral',
                'value' => 'Kazakhstan'
            ],
            [
                'text' => 'Asia/Qostanay',
                'value' => 'Kazakhstan'
            ],
            [
                'text' => 'Asia/Qyzylorda',
                'value' => 'Kazakhstan'
            ],
            [
                'text' => 'Africa/Nairobi',
                'value' => 'Kenya'
            ],
            [
                'text' => 'Pacific/Kanton',
                'value' => 'Kiribati'
            ],
            [
                'text' => 'Pacific/Kiritimati',
                'value' => 'Kiribati'
            ],
            [
                'text' => 'Pacific/Tarawa',
                'value' => 'Kiribati'
            ],
            [
                'text' => 'Democratic Peoples Republic of',
                'value' => 'Korea'
            ],
            [
                'text' => 'Asia/Kuwait',
                'value' => 'Kuwait'
            ],
            [
                'text' => 'Asia/Bishkek',
                'value' => 'Kyrgyzstan'
            ],
            [
                'text' => 'Asia/Vientiane',
                'value' => 'Lao Peoples Democratic Republic'
            ],
            [
                'text' => 'Europe/Riga',
                'value' => 'Latvia'
            ],
            [
                'text' => 'Asia/Beirut',
                'value' => 'Lebanon'
            ],
            [
                'text' => 'Africa/Maseru',
                'value' => 'Lesotho'
            ],
            [
                'text' => 'Africa/Monrovia',
                'value' => 'Liberia'
            ],
            [
                'text' => 'Africa/Tripoli',
                'value' => 'Libya'
            ],
            [
                'text' => 'Europe/Vaduz',
                'value' => 'Liechtenstein'
            ],
            [
                'text' => 'Europe/Vilnius',
                'value' => 'Lithuania'
            ],
            [
                'text' => 'Europe/Luxembourg',
                'value' => 'Luxembourg'
            ],
            [
                'text' => 'Asia/Macau',
                'value' => 'Macao'
            ],
            [
                'text' => 'the Former Yugoslav Republic of',
                'value' => 'Macedonia'
            ],
            [
                'text' => 'Indian/Antananarivo',
                'value' => 'Madagascar'
            ],
            [
                'text' => 'Africa/Blantyre',
                'value' => 'Malawi'
            ],
            [
                'text' => 'Asia/Kuala_Lumpur',
                'value' => 'Malaysia'
            ],
            [
                'text' => 'Asia/Kuching',
                'value' => 'Malaysia'
            ],
            [
                'text' => 'Indian/Maldives',
                'value' => 'Maldives'
            ],
            [
                'text' => 'Africa/Bamako',
                'value' => 'Mali'
            ],
            [
                'text' => 'Europe/Malta',
                'value' => 'Malta'
            ],
            [
                'text' => 'Pacific/Kwajalein',
                'value' => 'Marshall Islands'
            ],
            [
                'text' => 'Pacific/Majuro',
                'value' => 'Marshall Islands'
            ],
            [
                'text' => 'America/Martinique',
                'value' => 'Martinique'
            ],
            [
                'text' => 'Africa/Nouakchott',
                'value' => 'Mauritania'
            ],
            [
                'text' => 'Indian/Mauritius',
                'value' => 'Mauritius'
            ],
            [
                'text' => 'Indian/Mayotte',
                'value' => 'Mayotte'
            ],
            [
                'text' => 'America/Bahia_Banderas',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Cancun',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Chihuahua',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Hermosillo',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Matamoros',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Mazatlan',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Merida',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Mexico_City',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Monterrey',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Ojinaga',
                'value' => 'Mexico'
            ],
            [
                'text' => 'America/Tijuana',
                'value' => 'Mexico'
            ],
            [
                'text' => 'Europe/Monaco',
                'value' => 'Monaco'
            ],
            [
                'text' => 'Asia/Choibalsan',
                'value' => 'Mongolia'
            ],
            [
                'text' => 'Asia/Hovd',
                'value' => 'Mongolia'
            ],
            [
                'text' => 'Asia/Ulaanbaatar',
                'value' => 'Mongolia'
            ],
            [
                'text' => 'Europe/Podgorica',
                'value' => 'Montenegro'
            ],
            [
                'text' => 'America/Montserrat',
                'value' => 'Montserrat'
            ],
            [
                'text' => 'Africa/Casablanca',
                'value' => 'Morocco'
            ],
            [
                'text' => 'Africa/Maputo',
                'value' => 'Mozambique'
            ],
            [
                'text' => 'Asia/Yangon',
                'value' => 'Myanmar'
            ],
            [
                'text' => 'Africa/Windhoek',
                'value' => 'Namibia'
            ],
            [
                'text' => 'Pacific/Nauru',
                'value' => 'Nauru'
            ],
            [
                'text' => 'Asia/Kathmandu',
                'value' => 'Nepal'
            ],
            [
                'text' => 'Europe/Amsterdam',
                'value' => 'Netherlands'
            ],
            [
                'text' => 'Pacific/Noumea',
                'value' => 'New Caledonia'
            ],
            [
                'text' => 'Pacific/Auckland',
                'value' => 'New Zealand'
            ],
            [
                'text' => 'Pacific/Chatham',
                'value' => 'New Zealand'
            ],
            [
                'text' => 'America/Managua',
                'value' => 'Nicaragua'
            ],
            [
                'text' => 'Africa/Niamey',
                'value' => 'Niger'
            ],
            [
                'text' => 'Africa/Lagos',
                'value' => 'Nigeria'
            ],
            [
                'text' => 'Pacific/Niue',
                'value' => 'Niue'
            ],
            [
                'text' => 'Pacific/Norfolk',
                'value' => 'Norfolk Island'
            ],
            [
                'text' => 'Pacific/Saipan',
                'value' => 'Northern Mariana Islands'
            ],
            [
                'text' => 'Europe/Oslo',
                'value' => 'Norway'
            ],
            [
                'text' => 'Asia/Muscat',
                'value' => 'Oman'
            ],
            [
                'text' => 'Asia/Karachi',
                'value' => 'Pakistan'
            ],
            [
                'text' => 'Pacific/Palau',
                'value' => 'Palau'
            ],
            [
                'text' => 'America/Panama',
                'value' => 'Panama'
            ],
            [
                'text' => 'Pacific/Bougainville',
                'value' => 'Papua New Guinea'
            ],
            [
                'text' => 'Pacific/Port_Moresby',
                'value' => 'Papua New Guinea'
            ],
            [
                'text' => 'America/Asuncion',
                'value' => 'Paraguay'
            ],
            [
                'text' => 'America/Lima',
                'value' => 'Peru'
            ],
            [
                'text' => 'Asia/Manila',
                'value' => 'Philippines'
            ],
            [
                'text' => 'Pacific/Pitcairn',
                'value' => 'Pitcairn'
            ],
            [
                'text' => 'Europe/Warsaw',
                'value' => 'Poland'
            ],
            [
                'text' => 'Atlantic/Azores',
                'value' => 'Portugal'
            ],
            [
                'text' => 'Atlantic/Madeira',
                'value' => 'Portugal'
            ],
            [
                'text' => 'Europe/Lisbon',
                'value' => 'Portugal'
            ],
            [
                'text' => 'America/Puerto_Rico',
                'value' => 'Puerto Rico'
            ],
            [
                'text' => 'Asia/Qatar',
                'value' => 'Qatar'
            ],
            [
                'text' => 'Europe/Bucharest',
                'value' => 'Romania'
            ],
            [
                'text' => 'Asia/Anadyr',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Barnaul',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Chita',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Irkutsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Kamchatka',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Khandyga',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Krasnoyarsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Magadan',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Novokuznetsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Novosibirsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Omsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Sakhalin',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Srednekolymsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Tomsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Ust-Nera',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Vladivostok',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Yakutsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Asia/Yekaterinburg',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Europe/Astrakhan',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Europe/Kaliningrad',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Europe/Kirov',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Europe/Moscow',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Europe/Samara',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Europe/Saratov',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Europe/Ulyanovsk',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Europe/Volgograd',
                'value' => 'Russian Federation'
            ],
            [
                'text' => 'Africa/Kigali',
                'value' => 'Rwanda'
            ],
            [
                'text' => 'Indian/Reunion',
                'value' => 'Réunion'
            ],
            [
                'text' => 'America/St_Barthelemy',
                'value' => 'Saint Barthélemy'
            ],
            [
                'text' => 'Ascension and Tristan da Cunha',
                'value' => 'Saint Helena'
            ],
            [
                'text' => 'America/St_Kitts',
                'value' => 'Saint Kitts and Nevis'
            ],
            [
                'text' => 'America/St_Lucia',
                'value' => 'Saint Lucia'
            ],
            [
                'text' => 'America/Marigot',
                'value' => 'Saint Martin (French part)'
            ],
            [
                'text' => 'America/Miquelon',
                'value' => 'Saint Pierre and Miquelon'
            ],
            [
                'text' => 'America/St_Vincent',
                'value' => 'Saint Vincent and the Grenadines'
            ],
            [
                'text' => 'Pacific/Apia',
                'value' => 'Samoa'
            ],
            [
                'text' => 'Europe/San_Marino',
                'value' => 'San Marino'
            ],
            [
                'text' => 'Africa/Sao_Tome',
                'value' => 'Sao Tome and Principe'
            ],
            [
                'text' => 'Asia/Riyadh',
                'value' => 'Saudi Arabia'
            ],
            [
                'text' => 'Africa/Dakar',
                'value' => 'Senegal'
            ],
            [
                'text' => 'Europe/Belgrade',
                'value' => 'Serbia'
            ],
            [
                'text' => 'Indian/Mahe',
                'value' => 'Seychelles'
            ],
            [
                'text' => 'Africa/Freetown',
                'value' => 'Sierra Leone'
            ],
            [
                'text' => 'Asia/Singapore',
                'value' => 'Singapore'
            ],
            [
                'text' => 'America/Lower_Princes',
                'value' => 'Sint Maarten (Dutch part)'
            ],
            [
                'text' => 'Europe/Bratislava',
                'value' => 'Slovakia'
            ],
            [
                'text' => 'Europe/Ljubljana',
                'value' => 'Slovenia'
            ],
            [
                'text' => 'Pacific/Guadalcanal',
                'value' => 'Solomon Islands'
            ],
            [
                'text' => 'Africa/Mogadishu',
                'value' => 'Somalia'
            ],
            [
                'text' => 'Africa/Johannesburg',
                'value' => 'South Africa'
            ],
            [
                'text' => 'Atlantic/South_Georgia',
                'value' => 'South Georgia and the South Sandwich Islands'
            ],
            [
                'text' => 'Africa/Juba',
                'value' => 'South Sudan'
            ],
            [
                'text' => 'Africa/Ceuta',
                'value' => 'Spain'
            ],
            [
                'text' => 'Atlantic/Canary',
                'value' => 'Spain'
            ],
            [
                'text' => 'Europe/Madrid',
                'value' => 'Spain'
            ],
            [
                'text' => 'Asia/Colombo',
                'value' => 'Sri Lanka'
            ],
            [
                'text' => 'Africa/Khartoum',
                'value' => 'Sudan'
            ],
            [
                'text' => 'America/Paramaribo',
                'value' => 'Suriname'
            ],
            [
                'text' => 'Arctic/Longyearbyen',
                'value' => 'Svalbard and Jan Mayen'
            ],
            [
                'text' => 'Africa/Mbabane',
                'value' => 'Swaziland'
            ],
            [
                'text' => 'Europe/Stockholm',
                'value' => 'Sweden'
            ],
            [
                'text' => 'Europe/Zurich',
                'value' => 'Switzerland'
            ],
            [
                'text' => 'Asia/Damascus',
                'value' => 'Syrian Arab Republic'
            ],
            [
                'text' => 'Province of China',
                'value' => 'Taiwan'
            ],
            [
                'text' => 'Asia/Dushanbe',
                'value' => 'Tajikistan'
            ],
            [
                'text' => 'United Republic of',
                'value' => 'Tanzania'
            ],
            [
                'text' => 'Asia/Bangkok',
                'value' => 'Thailand'
            ],
            [
                'text' => 'Asia/Dili',
                'value' => 'Timor-Leste'
            ],
            [
                'text' => 'Africa/Lome',
                'value' => 'Togo'
            ],
            [
                'text' => 'Pacific/Fakaofo',
                'value' => 'Tokelau'
            ],
            [
                'text' => 'Pacific/Tongatapu',
                'value' => 'Tonga'
            ],
            [
                'text' => 'America/Port_of_Spain',
                'value' => 'Trinidad and Tobago'
            ],
            [
                'text' => 'Africa/Tunis',
                'value' => 'Tunisia'
            ],
            [
                'text' => 'Europe/Istanbul',
                'value' => 'Turkey'
            ],
            [
                'text' => 'Asia/Ashgabat',
                'value' => 'Turkmenistan'
            ],
            [
                'text' => 'America/Grand_Turk',
                'value' => 'Turks and Caicos Islands'
            ],
            [
                'text' => 'Pacific/Funafuti',
                'value' => 'Tuvalu'
            ],
            [
                'text' => 'Africa/Kampala',
                'value' => 'Uganda'
            ],
            [
                'text' => 'Europe/Kiev',
                'value' => 'Ukraine'
            ],
            [
                'text' => 'Europe/Simferopol',
                'value' => 'Ukraine'
            ],
            [
                'text' => 'Europe/Uzhgorod',
                'value' => 'Ukraine'
            ],
            [
                'text' => 'Europe/Zaporozhye',
                'value' => 'Ukraine'
            ],
            [
                'text' => 'Asia/Dubai',
                'value' => 'United Arab Emirates'
            ],
            [
                'text' => 'Europe/London',
                'value' => 'United Kingdom'
            ],
            [
                'text' => 'America/Adak',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Anchorage',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Boise',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Chicago',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Denver',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Detroit',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Indiana/Indianapolis',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Indiana/Knox',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Indiana/Marengo',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Indiana/Petersburg',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Indiana/Tell_City',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Indiana/Vevay',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Indiana/Vincennes',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Indiana/Winamac',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Juneau',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Kentucky/Louisville',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Kentucky/Monticello',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Los_Angeles',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Menominee',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Metlakatla',
                'value' => 'United States'
            ],
            [
                'text' => 'America/New_York',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Nome',
                'value' => 'United States'
            ],
            [
                'text' => 'America/North_Dakota/Beulah',
                'value' => 'United States'
            ],
            [
                'text' => 'America/North_Dakota/Center',
                'value' => 'United States'
            ],
            [
                'text' => 'America/North_Dakota/New_Salem',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Phoenix',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Sitka',
                'value' => 'United States'
            ],
            [
                'text' => 'America/Yakutat',
                'value' => 'United States'
            ],
            [
                'text' => 'Pacific/Honolulu',
                'value' => 'United States'
            ],
            [
                'text' => 'Pacific/Midway',
                'value' => 'United States Minor Outlying Islands'
            ],
            [
                'text' => 'United States Minor Outlying Islands',
                'value' => 'Pacific/Wake'
            ],
            [
                'text' => 'Uruguay',
                'value' => 'America/Montevideo'
            ],
            [
                'text' => 'Uzbekistan',
                'value' => 'Asia/Tashkent'
            ],
            [
                'text' => 'Vanuatu',
                'value' => 'Pacific/Efate'
            ],
            [
                'text' => 'Bolivarian Republic of',
                'value' => 'Venezuela'
            ],
            [
                'text' => 'Viet Nam',
                'value' => 'Asia/Ho_Chi_Minh'
            ],
            [
                'text' => 'Virgin Islands',
                'value' => 'British'
            ],
            [
                'text' => 'Virgin Islands',
                'value' => 'U.S.'
            ],
            [
                'text' => 'Wallis and Futuna',
                'value' => 'Pacific/Wallis'
            ],
            [
                'text' => 'Western Sahara',
                'value' => 'Africa/El_Aaiun'
            ],
            [
                'text' => 'Yemen',
                'value' => 'Asia/Aden'
            ],
            [
                'text' => 'Zambia',
                'value' => 'Africa/Lusaka'
            ],
            [
                'text' => 'Zimbabwe',
                'value' => 'Africa/Harare'
            ],
            [
                'text' => 'Åland Islands',
                'value' => 'Europe/Mariehamn'
            ]
        ];
        return [
            'System' => [
                'admin_mail' => [
                    'text',
                    'label' => 'Admin Mail:',
                    'formClass' => 'form-control',
                ],
                'email_host' => [
                    'text',
                    'label' => 'Email Host:',
                    'formClass' => 'form-control',
                ],
                'email_port' => [
                    'text',
                    'label' => 'Email Port:',
                    'formClass' => 'form-control',
                ],
                'email_username' => [
                    'email',
                    'label' => 'Email:',
                    'formClass' => 'form-control',
                ],
                'email_password' => [
                    'password',
                    'label' => 'Password Email:',
                    'formClass' => 'form-control',
                ],
                'email_from_addr' => [
                    'email',
                    'label' => 'From Email:',
                    'formClass' => 'form-control',
                ],
                'email_from_name' => [
                    'text',
                    'label' => 'From Name:',
                    'formClass' => 'form-control',
                ],
                'time_zone' => ['option',
                    'options' => $options,
                    'type' => 'select2',
                    'label' => 'Time Zone:',
                    'formClass' => 'form-select',
                ],
            ],
        ];
    }
}