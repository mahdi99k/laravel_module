<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Deactive = 'deactive';
    case Ban = 'ban';
}
