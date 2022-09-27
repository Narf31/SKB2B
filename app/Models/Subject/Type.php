<?php

namespace App\Models\Subject;

class Type
{
    const PHYSICAL  = 1;
    const JURIDICAL = 2;
    const TYPE_MY = 0;
    const TYPE_PARTNER = 1;

    const WORK = 0;
    const NOT_WORK = 1;

    const STATUS_SENDER = 0;
    const STATUS_RECEIVER = 1;

    const STATUS_SENDER_PAYMENT_TUPE_DEFAULT = 0;
    const STATUS_SENDER_PAYMENT_TUPE_CAUTION = 1;
    const STATUS_SENDER_PAYMENT_TUPE_TRUST = 2;


    const STATUS_SENDER_TUPE_COUNT_TRANSPORTATIONS_DAY = 0;
    const STATUS_SENDER_TUPE_COUNT_TRANSPORTATIONS_MONTH = 1;
    const STATUS_SENDER_TUPE_COUNT_TRANSPORTATIONS_YEAR = 2;

}