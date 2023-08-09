<?php

namespace App\Entity;

enum MeetingStatus: string
{
    case Open = 'OPEN';
    case Full = 'FULL';
    case InSession = 'IN_SESSION';
    case Done = 'DONE';
}
