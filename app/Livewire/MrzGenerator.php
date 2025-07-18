<?php

namespace App\Livewire;

use Livewire\Component;

class MrzGenerator extends Component
{
    public $passportNumber;
    public $birthDate;
    public $expiryDate;
    public $countryCode = 'IDN';
    public $gender = 'M';
    public $name;
    public $mrzResult;
    public $birthDateInput;
    public $expiryDateInput;

    public $countries = [];

    public function mount()
    {
        $this->countries = $this->getCountryList();
    }

    public function generate()
    {
        $this->birthDate = $this->formatDateToYYMMDD($this->birthDateInput);
        $this->expiryDate = $this->formatDateToYYMMDD($this->expiryDateInput);

        $this->validate([
            'passportNumber' => 'required|string',
            'birthDate' => 'required|regex:/^\d{6}$/',
            'expiryDate' => 'required|regex:/^\d{6}$/',
            'countryCode' => 'required|string',
            'gender' => 'required|in:M,F',
            'name' => 'required|string',
        ]);

        $this->mrzResult = $this->generateMRZ(
            strtoupper($this->passportNumber),
            $this->birthDate,
            $this->expiryDate,
            $this->countryCode,
            $this->gender,
            strtoupper($this->name)
        );
    }


    private function computeCheckDigit($value)
    {
        $weights = [7, 3, 1];
        $total = 0;
        $value = str_split($value);
        foreach ($value as $i => $char) {
            $num = is_numeric($char) ? (int)$char : ord($char) - 55;
            $total += $num * $weights[$i % 3];
        }
        return $total % 10;
    }

    private function formatDateToYYMMDD($date)
    {
        if (!$date) return null;

        $d = \Carbon\Carbon::parse($date);
        return $d->format('ymd'); // YYMMDD
    }


    private function generateMRZ($passportNumber, $birthDate, $expiryDate, $countryCode, $gender, $name)
    {
        $passportCheckDigit = $this->computeCheckDigit($passportNumber);
        $birthCheckDigit = $this->computeCheckDigit($birthDate);
        $expiryCheckDigit = $this->computeCheckDigit($expiryDate);

        $finalCheckString = $passportNumber . $passportCheckDigit . $birthDate . $birthCheckDigit . $expiryDate . $expiryCheckDigit;
        $finalCheckDigit = $this->computeCheckDigit($finalCheckString);

        $formattedName = str_replace(' ', '<', $name);
        $formattedName = str_pad($formattedName, 39, '<');

        $line1 = "P<{$countryCode}" . substr($formattedName, 0, 39);
        $line2 = "{$passportNumber}{$passportCheckDigit}{$countryCode}{$birthDate}{$birthCheckDigit}{$gender}{$expiryDate}{$expiryCheckDigit}<<<<<<<<{$finalCheckDigit}";

        return $line1 . "\n" . $line2;
    }

    private function getCountryList()
    {
        return [
            'Afghanistan' => 'AFG',
            'Åland Islands' => 'ALA',
            'Albania' => 'ALB',
            'Algeria' => 'DZA',
            'American Samoa' => 'ASM',
            'Andorra' => 'AND',
            'Angola' => 'AGO',
            'Anguilla' => 'AIA',
            'Antarctica' => 'ATA',
            'Antigua and Barbuda' => 'ATG',
            'Argentina' => 'ARG',
            'Armenia' => 'ARM',
            'Aruba' => 'ABW',
            'Australia' => 'AUS',
            'Austria' => 'AUT',
            'Azerbaijan' => 'AZE',
            'Bahamas' => 'BHS',
            'Bahrain' => 'BHR',
            'Bangladesh' => 'BGD',
            'Barbados' => 'BRB',
            'Belarus' => 'BLR',
            'Belgium' => 'BEL',
            'Belize' => 'BLZ',
            'Benin' => 'BEN',
            'Bermuda' => 'BMU',
            'Bhutan' => 'BTN',
            'Bolivia (Plurinational State of)' => 'BOL',
            'Bonaire, Sint Eustatius and Saba' => 'BES',
            'Bosnia and Herzegovina' => 'BIH',
            'Botswana' => 'BWA',
            'Bouvet Island' => 'BVT',
            'Brazil' => 'BRA',
            'British Indian Ocean Territory' => 'IOT',
            'Brunei Darussalam' => 'BRN',
            'Bulgaria' => 'BGR',
            'Burkina Faso' => 'BFA',
            'Burundi' => 'BDI',
            'Cabo Verde' => 'CPV',
            'Cambodia' => 'KHM',
            'Cameroon' => 'CMR',
            'Canada' => 'CAN',
            'Cayman Islands' => 'CYM',
            'Central African Republic' => 'CAF',
            'Chad' => 'TCD',
            'Chile' => 'CHL',
            'China' => 'CHN',
            'Christmas Island' => 'CXR',
            'Cocos (Keeling) Islands' => 'CCK',
            'Colombia' => 'COL',
            'Comoros' => 'COM',
            'Congo' => 'COG',
            'Congo, Democratic Republic of the' => 'COD',
            'Cook Islands' => 'COK',
            'Costa Rica' => 'CRI',
            'Côte d\'Ivoire' => 'CIV',
            'Croatia' => 'HRV',
            'Cuba' => 'CUB',
            'Curaçao' => 'CUW',
            'Cyprus' => 'CYP',
            'Czechia' => 'CZE',
            'Denmark' => 'DNK',
            'Djibouti' => 'DJI',
            'Dominica' => 'DMA',
            'Dominican Republic' => 'DOM',
            'Ecuador' => 'ECU',
            'Egypt' => 'EGY',
            'El Salvador' => 'SLV',
            'Equatorial Guinea' => 'GNQ',
            'Eritrea' => 'ERI',
            'Estonia' => 'EST',
            'Eswatini' => 'SWZ',
            'Ethiopia' => 'ETH',
            'Falkland Islands (Malvinas)' => 'FLK',
            'Faroe Islands' => 'FRO',
            'Fiji' => 'FJI',
            'Finland' => 'FIN',
            'France' => 'FRA',
            'French Guiana' => 'GUF',
            'French Polynesia' => 'PYF',
            'French Southern Territories' => 'ATF',
            'Gabon' => 'GAB',
            'Gambia' => 'GMB',
            'Georgia' => 'GEO',
            'Germany' => 'DEU',
            'Ghana' => 'GHA',
            'Gibraltar' => 'GIB',
            'Greece' => 'GRC',
            'Greenland' => 'GRL',
            'Grenada' => 'GRD',
            'Guadeloupe' => 'GLP',
            'Guam' => 'GUM',
            'Guatemala' => 'GTM',
            'Guernsey' => 'GGY',
            'Guinea' => 'GIN',
            'Guinea-Bissau' => 'GNB',
            'Guyana' => 'GUY',
            'Haiti' => 'HTI',
            'Heard Island and McDonald Islands' => 'HMD',
            'Holy See' => 'VAT',
            'Honduras' => 'HND',
            'Hong Kong' => 'HKG',
            'Hungary' => 'HUN',
            'Iceland' => 'ISL',
            'India' => 'IND',
            'Indonesia' => 'IDN',
            'Iran (Islamic Republic of)' => 'IRN',
            'Iraq' => 'IRQ',
            'Ireland' => 'IRL',
            'Isle of Man' => 'IMN',
            'Israel' => 'ISR',
            'Italy' => 'ITA',
            'Jamaica' => 'JAM',
            'Japan' => 'JPN',
            'Jersey' => 'JEY',
            'Jordan' => 'JOR',
            'Kazakhstan' => 'KAZ',
            'Kenya' => 'KEN',
            'Kiribati' => 'KIR',
            'Korea (Democratic People\'s Republic of)' => 'PRK',
            'Korea (Republic of)' => 'KOR',
            'Kuwait' => 'KWT',
            'Kyrgyzstan' => 'KGZ',
            'Lao People\'s Democratic Republic' => 'LAO',
            'Latvia' => 'LVA',
            'Lebanon' => 'LBN',
            'Lesotho' => 'LSO',
            'Liberia' => 'LBR',
            'Libya' => 'LBY',
            'Liechtenstein' => 'LIE',
            'Lithuania' => 'LTU',
            'Luxembourg' => 'LUX',
            'Macao' => 'MAC',
            'Madagascar' => 'MDG',
            'Malawi' => 'MWI',
            'Malaysia' => 'MYS',
            'Maldives' => 'MDV',
            'Mali' => 'MLI',
            'Malta' => 'MLT',
            'Marshall Islands' => 'MHL',
            'Martinique' => 'MTQ',
            'Mauritania' => 'MRT',
            'Mauritius' => 'MUS',
            'Mayotte' => 'MYT',
            'Mexico' => 'MEX',
            'Micronesia (Federated States of)' => 'FSM',
            'Moldova (Republic of)' => 'MDA',
            'Monaco' => 'MCO',
            'Mongolia' => 'MNG',
            'Montenegro' => 'MNE',
            'Montserrat' => 'MSR',
            'Morocco' => 'MAR',
            'Mozambique' => 'MOZ',
            'Myanmar' => 'MMR',
            'Namibia' => 'NAM',
            'Nauru' => 'NRU',
            'Nepal' => 'NPL',
            'Netherlands' => 'NLD',
            'New Caledonia' => 'NCL',
            'New Zealand' => 'NZL',
            'Nicaragua' => 'NIC',
            'Niger' => 'NER',
            'Nigeria' => 'NGA',
            'Niue' => 'NIU',
            'Norfolk Island' => 'NFK',
            'North Macedonia' => 'MKD',
            'Northern Mariana Islands' => 'MNP',
            'Norway' => 'NOR',
            'Oman' => 'OMN',
            'Pakistan' => 'PAK',
            'Palau' => 'PLW',
            'Panama' => 'PAN',
            'Papua New Guinea' => 'PNG',
            'Paraguay' => 'PRY',
            'Peru' => 'PER',
            'Philippines' => 'PHL',
            'Pitcairn' => 'PCN',
            'Poland' => 'POL',
            'Portugal' => 'PRT',
            'Puerto Rico' => 'PRI',
            'Qatar' => 'QAT',
            'Réunion' => 'REU',
            'Romania' => 'ROU',
            'Russian Federation' => 'RUS',
            'Rwanda' => 'RWA',
            'Saint Barthélemy' => 'BLM',
            'Saint Helena, Ascension and Tristan da Cunha' => 'SHN',
            'Saint Kitts and Nevis' => 'KNA',
            'Saint Lucia' => 'LCA',
            'Saint Martin (French part)' => 'MAF',
            'Saint Pierre and Miquelon' => 'SPM',
            'Saint Vincent and the Grenadines' => 'VCT',
            'Samoa' => 'WSM',
            'San Marino' => 'SMR',
            'Sao Tome and Principe' => 'STP',
            'Saudi Arabia' => 'SAU',
            'Senegal' => 'SEN',
            'Serbia' => 'SRB',
            'Seychelles' => 'SYC',
            'Sierra Leone' => 'SLE',
            'Singapore' => 'SGP',
            'Sint Maarten (Dutch part)' => 'SXM',
            'Slovakia' => 'SVK',
            'Slovenia' => 'SVN',
            'Solomon Islands' => 'SLB',
            'Somalia' => 'SOM',
            'South Africa' => 'ZAF',
            'South Georgia and the South Sandwich Islands' => 'SGS',
            'South Sudan' => 'SSD',
            'Spain' => 'ESP',
            'Sri Lanka' => 'LKA',
            'Sudan' => 'SDN',
            'Suriname' => 'SUR',
            'Svalbard and Jan Mayen' => 'SJM',
            'Sweden' => 'SWE',
            'Switzerland' => 'CHE',
            'Syrian Arab Republic' => 'SYR',
            'Taiwan, Province of China' => 'TWN',
            'Tajikistan' => 'TJK',
            'Tanzania, United Republic of' => 'TZA',
            'Thailand' => 'THA',
            'Timor-Leste' => 'TLS',
            'Togo' => 'TGO',
            'Tokelau' => 'TKL',
            'Tonga' => 'TON',
            'Trinidad and Tobago' => 'TTO',
            'Tunisia' => 'TUN',
            'Türkiye' => 'TUR',
            'Turkmenistan' => 'TKM',
            'Tuvalu' => 'TUV',
            'Uganda' => 'UGA',
            'Ukraine' => 'UKR',
            'United Arab Emirates' => 'ARE',
            'United Kingdom of Great Britain and Northern Ireland' => 'GBR',
            'United States of America' => 'USA',
            'United States Minor Outlying Islands' => 'UMI',
            'Uruguay' => 'URY',
            'Uzbekistan' => 'UZB',
            'Vanuatu' => 'VUT',
            'Venezuela (Bolivarian Republic of)' => 'VEN',
            'Viet Nam' => 'VNM',
            'Virgin Islands (British)' => 'VGB',
            'Virgin Islands (U.S.)' => 'VIR',
            'Wallis and Futuna' => 'WLF',
            'Western Sahara' => 'ESH',
            'Yemen' => 'YEM',
            'Zambia' => 'ZMB',
            'Zimbabwe' => 'ZWE'
        ];
    }

    public function render()
    {
        return view('livewire.mrz-generator');
    }
}
