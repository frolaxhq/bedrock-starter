<?php

declare(strict_types=1);

namespace Modules\QrCode;

use Frolax\Bedrock\Providers\Module\ModuleServiceProvider;

class QrCodeServiceProvider extends ModuleServiceProvider
{
    protected string $moduleKey = 'qr-code';

    protected string $modulePath = __DIR__.'/..';
}
