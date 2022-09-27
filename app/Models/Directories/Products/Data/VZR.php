<?php

namespace App\Models\Directories\Products\Data;

use App\Models\Security\Security;
use App\Models\Settings\Country;
use App\Models\Settings\Currency;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $next_act
 * @property string $default_purpose_payment
 * @property string $inn
 * @property float $limit_year
 * @property float $spent_limit_year
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereNextAct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereDefaultPurposePayment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereSpentLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereIsActual($value)
 */
class VZR extends Model
{
    protected $table = 'products_vzr';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function currency() {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function сountry() {
        return $this->hasOne(Country::class, 'id', 'сountry_id');
    }

    public function setDefault()
    {


        return true;
    }

    const TYPE_AGR = [
        1 => 'Однократный',
        2 => 'Годовой',
    ];

    const PROGRAMS = [
        1 => [
            1 => 'A',
            2 => 'B1',
            3 => 'B2',
            4 => 'C',
            5 => 'D',
        ],
        2 => [
            6 => 'B2 MULTI',
        ],
    ];

    const AMOUNT = [
        1 => [
            3000 => '3 000',
            5000 => '5 000',
            15000 => '15 000',
            30000 => '30 000',
            35000 => '35 000',
            40000 => '40 000',
            50000 => '50 000',
            100000 => '100 000',
        ],
        2 => [
            30000 => '30 000',
            35000 => '35 000',
            40000 => '40 000',
            50000 => '50 000',
        ],
        3 => [
            30000 => '30 000',
            35000 => '35 000',
            40000 => '40 000',
            50000 => '50 000',
            100000 => '100 000',
        ],
    ];

    const DAY_TO = [
        30 => '30',
        60 => '60',
        90 => '90',
        180 => '180',
    ];

    const FLIGHT_DELAY_PROGRAM = [
        0 => 'Нет',
        1 => 'ЗР1',
        2 => 'ЗР2',
        3 => 'ЗР3',
    ];

    const FLIGHT_DELAY_AMOUNT = [
        150 => '150',
        300 => '300',
        500 => '500',
        750 => '750',
    ];

    const MISSED_FLIGHT_PROGRAM = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const MISSED_FLIGHT_AMOUNT = [
        150 => '150',
        300 => '300',
        500 => '500',
        750 => '750',
    ];

    const BAGGAGE_PROGRAM = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const BAGGAGE_AMOUNT = [
        500 => '500',
        1000 => '1 000',
        1500 => '1 500',
        2000 => '2 000',
    ];

    const CIVIL_RESPONSIBILITY_PROGRAM = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const CIVIL_RESPONSIBILITY_AMOUNT = [
        10000 => '10 000',
        30000 => '30 000',
        50000 => '50 000',
        100000 => '100 000',
    ];

    const LEGAL_AID_PROGRAM = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const LEGAL_AID_AMOUNT = [
        1000 => '1 000',
        2000 => '2 000',
        3000 => '3 000',
        5000 => '5 000',
    ];


    const CANCEL_TOUR_PROGRAM = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const CANCEL_TOUR_AMOUNT = [
        100 => '100',
        150 => '150',
        200 => '200',
        250 => '250',
    ];

    const NS_PROGRAM = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const NS_AMOUNT = [
        1000 => '1 000',
        3000 => '3 000',
        5000 => '5 000',
        10000 => '10 000',
    ];

    const CANCEL_TRIP_PROGRAM = [
        0 => 'Нет',
        1 => 'ОП1',
        2 => 'ОП2',
        3 => 'ОП3',
        4 => 'ОП4',
        5 => 'ОП5',
    ];


    const SPORTS = [
        0 => 'Нет',
        1 => 'Спорт 1',
        2 => 'Спорт 2',
        3 => 'Спорт 3',
        4 => 'Спорт 4',
        5 => 'Спорт 5',
        6 => 'Спорт 6',
        7 => 'Спорт 7',
    ];


    const PROFESSIONS = [
        0 => 'Нет',
        1 => 'Профессия 1',
        2 => 'Профессия 2',
        3 => 'Профессия 3',
        4 => 'Профессия 4',
    ];


    const FRANCHISE = [
        0 => 'Нет',
        1 => '30',
        2 => '50',
        3 => '100',
    ];


    const OPTIONS = [
        'is_leisure' => 'Активный отдых',
        'is_chronic_diseases' => 'Хронич. заболевания',
        'is_pregnancy' => 'Беременность',
        'is_science' => 'Наука',
        'is_children' => 'Дети',
        'is_alcohol' => 'Алкоголь',
        'is_covid19' => 'COVID-19',
    ];


    const SPORTS_TEXT = [
        0 => '',
        1 => 'Аэробика, бадминтон, бег, гольф, городки, гребля (академическая, на байдарках и каноэ), настольный теннис, плавание/синхронное плавание, пожарно-прикладной радиоспорт, стрельба, спортивное ориентирование, пятиборье, бальные танцы',
        2 => 'Бейсбол, водное поло, водные лыжи, волейбол, конькобежный/ шорт – трек, лыжные гонки /биатлон, прыжки в воду, сквош, софтбол, теннис, фехтование, фигурное катание, картинг',
        3 => 'Акробатика, атлетика тяжелая и легкая, баскетбол, батут, гимнастика, гиревой спорт, дайвинг, конный, парусный, серфинг, пятиборье, триатлон, балет',
        4 => 'Бобслей /санный спорт, буер, гандбол, регби, роллер, силовое троеборье, футбол.',
        5 => 'Автоспорт (автокросс, автогонки, багги, карт), альпинизм, борьба (классическая, вольная), велоспорт, горные лыжи, дзюдо, дельтапланеризм, парашютный, парапланеризм, планерный спорт, прыжки на лыжах с трамплина, рафтинг, самбо, скалолазание, фристайл, хоккей (все виды).',
        6 => 'Бокс, каратэ-до, кик-боксинг, рукопашный бой, тхэквондо, ушу.',
        7 => 'Путешествия (спец. маршруты, переходы)',
    ];

    const PROFESSIONS_TEXT = [
        0 => '',
        1 => 'Сидячие профессии с редким перемещением: служащие, инженерно-технические работники (не связанные непосредственно с процессом производства), руководители среднего звена, нотариусы, экономисты, бухгалтеры, библиотекари, программисты, работники гостиничного сервиса, дизайнеры, техники по обслуживанию офисов, работники литературы, искусства, образования, медицинских и лечебно – профилактических учреждений (кроме врачей скорой помощи), воспитатели, домохозяйки.',
        2 => 'Работники ручного труда в мастерских и на промышленных предприятиях (без использования механических средств). Работники физического труда (без использования взрывоопасных материалов и травмоопасного оборудования). Слесари. Водители транспортных средств, не занимающиеся разгрузочно-погрузочными работами. Работники служб питания, вахтёры, лифтёры, посыльные, курьеры.',
        3 => 'Профессии, связанные с физическим трудом или использованием механических средств, взрывоопасных материалов. Сварщики, фрезеровщики, токари, крановщики, жестянщики, монтажники, кузнецы. Лица, работающие на высоте более 5 метров. Антеннщики, электрики, прорабы, каменщики, литейщики. Работники телефонных станций, наладчики электролиний.',
        4 => 'Профессии повышенной степени риска. Работники служб безопасности. Руководители коммерческих структур. Инкассаторы. Летчики, водолазы. Подземные рабочие (спелеологи, геологи, шахтеры). Строители мостов, тоннелей, разрушители, каменотесы, пожарники, пиротехники, вальщики леса, перевозчики леса, пильщики. Лица, деятельность которых связана с использованием взрывных устройств или высоким напряжением.',
    ];

}
