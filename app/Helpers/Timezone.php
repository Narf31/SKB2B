<?php

namespace App\Helpers;


class Timezone
{
    public $timezoneList = [
        'Midway Island'                   => 'Pacific/Midway',
        'Samoa'                           => 'Pacific/Samoa',
        'Hawaii'                          => 'Pacific/Honolulu',
        'Alaska'                          => 'US/Alaska',
        'Pacific Time (US &amp; Canada)'  => 'America/Los_Angeles',
        'Tijuana'                         => 'America/Tijuana',
        'Arizona'                         => 'US/Arizona',
        'Chihuahua'                       => 'America/Chihuahua',
        'Mazatlan'                        => 'America/Mazatlan',
        'Mountain Time (US &amp; Canada)' => 'US/Mountain',
        'Central America'                 => 'America/Managua',
        'Central Time (US &amp; Canada)'  => 'US/Central',
        'Mexico City'                     => 'America/Mexico_City',
        'Monterrey'                       => 'America/Monterrey',
        'Saskatchewan'                    => 'Canada/Saskatchewan',
        'Bogota'                          => 'America/Bogota',
        'Eastern Time (US &amp; Canada)'  => 'US/Eastern',
        'Indiana (East)'                  => 'US/East-Indiana',
        'Lima'                            => 'America/Lima',
        'Atlantic Time (Canada)'          => 'Canada/Atlantic',
        'Caracas'                         => 'America/Caracas',
        'Santiago'                        => 'America/Santiago',
        'Newfoundland'                    => 'Canada/Newfoundland',
        'Brasilia'                        => 'America/Sao_Paulo',
        'Buenos Aires'                    => 'America/Argentina/Buenos_Aires',
        'Greenland'                       => 'America/Godthab',
        'Mid-Atlantic'                    => 'America/Noronha',
        'Azores'                          => 'Atlantic/Azores',
        'Cape Verde Is.'                  => 'Atlantic/Cape_Verde',
        'Casablanca'                      => 'Africa/Casablanca',
        'Edinburgh'                       => 'Europe/London',
        'Greenwich Mean Time : Dublin'    => 'Etc/Greenwich',
        'Lisbon'                          => 'Europe/Lisbon',
        'London'                          => 'Europe/London',
        'Monrovia'                        => 'Africa/Monrovia',
        'UTC'                             => 'UTC',
        'Amsterdam'                       => 'Europe/Amsterdam',
        'Belgrade'                        => 'Europe/Belgrade',
        'Berlin'                          => 'Europe/Berlin',
        'Bratislava'                      => 'Europe/Bratislava',
        'Brussels'                        => 'Europe/Brussels',
        'Budapest'                        => 'Europe/Budapest',
        'Copenhagen'                      => 'Europe/Copenhagen',
        'Ljubljana'                       => 'Europe/Ljubljana',
        'Madrid'                          => 'Europe/Madrid',
        'Paris'                           => 'Europe/Paris',
        'Prague'                          => 'Europe/Prague',
        'Rome'                            => 'Europe/Rome',
        'Sarajevo'                        => 'Europe/Sarajevo',
        'Skopje'                          => 'Europe/Skopje',
        'Stockholm'                       => 'Europe/Stockholm',
        'Vienna'                          => 'Europe/Vienna',
        'Warsaw'                          => 'Europe/Warsaw',
        'West Central Africa'             => 'Africa/Lagos',
        'Zagreb'                          => 'Europe/Zagreb',
        'Athens'                          => 'Europe/Athens',
        'Bucharest'                       => 'Europe/Bucharest',
        'Cairo'                           => 'Africa/Cairo',
        'Harare'                          => 'Africa/Harare',
        'Helsinki'                        => 'Europe/Helsinki',
        'Istanbul'                        => 'Europe/Istanbul',
        'Jerusalem'                       => 'Asia/Jerusalem',
        'Pretoria'                        => 'Africa/Johannesburg',
        'Riga'                            => 'Europe/Riga',
        'Sofia'                           => 'Europe/Sofia',
        'Tallinn'                         => 'Europe/Tallinn',
        'Vilnius'                         => 'Europe/Vilnius',
        'Baghdad'                         => 'Asia/Baghdad',
        'Kuwait'                          => 'Asia/Kuwait',
        'Minsk'                           => 'Europe/Minsk',
        'Nairobi'                         => 'Africa/Nairobi',
        'Riyadh'                          => 'Asia/Riyadh',
        'Volgograd'                       => 'Europe/Volgograd',
        'Tehran'                          => 'Asia/Tehran',
        'Baku'                            => 'Asia/Baku',
        'Moscow'                          => 'Europe/Moscow',
        'Muscat'                          => 'Asia/Muscat',
        'Tbilisi'                         => 'Asia/Tbilisi',
        'Yerevan'                         => 'Asia/Yerevan',
        'Kabul'                           => 'Asia/Kabul',
        'Islamabad'                       => 'Asia/Karachi',
        'Tashkent'                        => 'Asia/Tashkent',
        'Chennai'                         => 'Asia/Calcutta',
        'Kolkata'                         => 'Asia/Kolkata',
        'Kathmandu'                       => 'Asia/Katmandu',
        'Almaty'                          => 'Asia/Almaty',
        'Dhaka'                           => 'Asia/Dhaka',
        'Ekaterinburg'                    => 'Asia/Yekaterinburg',
        'Rangoon'                         => 'Asia/Rangoon',
        'Hanoi'                           => 'Asia/Bangkok',
        'Jakarta'                         => 'Asia/Jakarta',
        'Novosibirsk'                     => 'Asia/Novosibirsk',
        'Beijing'                         => 'Asia/Hong_Kong',
        'Chongqing'                       => 'Asia/Chongqing',
        'Krasnoyarsk'                     => 'Asia/Krasnoyarsk',
        'Kuala Lumpur'                    => 'Asia/Kuala_Lumpur',
        'Perth'                           => 'Australia/Perth',
        'Singapore'                       => 'Asia/Singapore',
        'Taipei'                          => 'Asia/Taipei',
        'Ulaan Bataar'                    => 'Asia/Ulan_Bator',
        'Urumqi'                          => 'Asia/Urumqi',
        'Irkutsk'                         => 'Asia/Irkutsk',
        'Seoul'                           => 'Asia/Seoul',
        'Tokyo'                           => 'Asia/Tokyo',
        'Adelaide'                        => 'Australia/Adelaide',
        'Darwin'                          => 'Australia/Darwin',
        'Brisbane'                        => 'Australia/Brisbane',
        'Canberra'                        => 'Australia/Canberra',
        'Guam'                            => 'Pacific/Guam',
        'Hobart'                          => 'Australia/Hobart',
        'Melbourne'                       => 'Australia/Melbourne',
        'Port Moresby'                    => 'Pacific/Port_Moresby',
        'Sydney'                          => 'Australia/Sydney',
        'Yakutsk'                         => 'Asia/Yakutsk',
        'Vladivostok'                     => 'Asia/Vladivostok',
        'Auckland'                        => 'Pacific/Auckland',
        'Fiji'                            => 'Pacific/Fiji',
        'International Date Line West'    => 'Pacific/Kwajalein',
        'Kamchatka'                       => 'Asia/Kamchatka',
        'Magadan'                         => 'Asia/Magadan',
        'Marshall Is.'                    => 'Pacific/Fiji',
        'Nukualofa'                       => 'Pacific/Tongatapu'
    ];

    private function getTimezoneOffset($timezone)
    {
        $dateTimeUtc  = new \DateTime("now", new \DateTimeZone("UTC"));
        $dateTimeZone = new \DateTimeZone($timezone);
        $offset       = $dateTimeZone->getOffset($dateTimeUtc) / 60 / 60;
        return $offset;
    }

    public function options()
    {
        $options = [];
        foreach ($this->timezoneList as $title => $timezone) {
            $offset                    = $this->getTimezoneOffset($timezone);
            $titleWithOffset           = '(UTC ' . $this->getSign($offset) . $this->convertTime($offset) . ') ' . $title;
            $options[$titleWithOffset] = $timezone;
        }

        uasort($options, function ($a, $b) {
            return $this->getTimezoneOffset($a) > $this->getTimezoneOffset($b);
        });

        return $options;
    }

    private function getSign($offset)
    {
        return ($offset > 0) ? '+' : '';
    }

    private function convertTime($dec)
    {
        $seconds = ($dec * 3600);
        $hours   = floor($dec);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        return $this->lz($hours) . ":" . $this->lz($minutes);
    }

// lz = leading zero
    private function lz($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }


}