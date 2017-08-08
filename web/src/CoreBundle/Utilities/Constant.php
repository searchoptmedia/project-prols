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

    const REQUEST_SLEAVE             = 1;
    const REQUEST_VLEAVE             = 2;
    const REQUEST_ACCESS             = 3;
    const REQUEST_MEETING            = 4;
    const REQUEST_EMERGENCY          = 5;

    const ROLE_ADMIN                = 'ADMIN';

    const GMAIL_EMAIL_ADDRESS       = 'no-reply@searchoptmedia.com';

    /** Date Format */
    const DATE_FORMAT_MID          = 'Y-m-d 00:00:00';
    const DATE_FORMAT_NIGHT        = 'Y-m-d 23:59:59';

    const HA_EVENT_TAG_ADD              = 'tag-create';
    const HA_EVENT_TAG_EMAIL            = 'tag-send-email';
    const HA_EVENT_TAG_STAT_UPDATE      = 'tag-update-status';

    const CODE_SUCCESS               = 200;
    const CODE_NO_CHANGE             = 230;
    const CODE_FORBIDDEN             = 403;
    const CODE_ERROR                 = 500;

}