<?php

declare(strict_types=1);

namespace Modules\QrCode\Tests\Feature;

use Frolax\Bedrock\Contracts\Module\ModuleManager;
use Frolax\Bedrock\Services\Module\ModuleBootstrapper;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class QrCodeModuleTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_generates_an_svg_with_the_private_sdk(): void
    {
        $manager = app(ModuleManager::class);
        $manager->install('qr-code');
        $manager->enable('qr-code');
        app(ModuleBootstrapper::class)->bootstrap();

        $this->get('/qr-code/svg?text=Bedrock')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml; charset=UTF-8')
            ->assertSee('<svg', false);
    }
}
