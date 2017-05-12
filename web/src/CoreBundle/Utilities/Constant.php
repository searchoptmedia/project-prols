<?php

namespace CoreBundle\Utilities;

class Constant
{
    const EVENT_TYPE_HOLIDAY        = 1;
    const EVENT_TYPE_INTERNAL       = 2;
    const EVENT_TYPE_MEETING        = 3;
    const EVENT_HOLIDAY_ID          = 1;
    const EVENT_INTERNAL_ID         = 2;
    const EVENT_MEETING_ID          = 3;

    const STATUS_ACTIVE             = 1;
    const STATUS_INACTIVE           = -1;
    const STATUS_PENDING            = 2;
    const STATUS_APPROVED           = 3;
    const STATUS_DECLINED           = 4;

    const ROLE_ADMIN                = 'ADMIN';

    const GMAIL_EMAIL_ADDRESS       = 'no-reply@searchoptmedia.com';

    /** Date Format */
    const DATE_FORMAT_MID          = 'Y-m-d 00:00:00';
    const DATE_FORMAT_NIGHT        = 'Y-m-d 23:59:59';

}