<?php

namespace Tests\Feature;

use Composer\InstalledVersions;
use Frolax\Bedrock\Contracts\Module\ModuleManager;
use Frolax\Bedrock\Services\Module\ModuleBootstrapper;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class QrCodeModuleTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_renders_a_qr_code_using_its_isolated_dependency(): void
    {
        $this->enableModule();

        $this->get('/qr-code?text=Bedrock')
            ->assertOk()
            ->assertSee('data:image/svg+xml;base64,', false)
            ->assertSee('BedrockPrivate', false);

        $this->assertFalse(InstalledVersions::isInstalled('bacon/bacon-qr-code'));
    }

    public function test_downloads_an_svg_for_bengali_text(): void
    {
        $this->enableModule();

        $this->get('/qr-code/svg?'.http_build_query(['text' => 'আমার Bedrock module']))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml; charset=UTF-8')
            ->assertHeader('Content-Disposition', 'attachment; filename="qr-code.svg"')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertSee('<svg', false);
    }

    #[DataProvider('invalidText')]
    public function test_rejects_invalid_text(mixed $text): void
    {
        $this->enableModule();

        $this->getJson('/qr-code/svg?'.http_build_query(['text' => $text]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('text');
    }

    public static function invalidText(): array
    {
        return [
            'empty' => [''],
            'too long' => [str_repeat('x', 513)],
            'array' => [['unexpected']],
        ];
    }

    public function test_escapes_user_text_in_the_form(): void
    {
        $this->enableModule();
        $text = '<script>alert("unsafe")</script>';

        $this->get('/qr-code?'.http_build_query(['text' => $text]))
            ->assertOk()
            ->assertSee($text)
            ->assertDontSee($text, false);
    }

    public function test_disabled_module_routes_are_not_available(): void
    {
        $this->enableModule();
        app(ModuleManager::class)->disable('qr-code');

        $this->get('/qr-code')->assertNotFound();
        $this->get('/qr-code/svg')->assertNotFound();
    }

    private function enableModule(): void
    {
        $manager = app(ModuleManager::class);
        $manager->install('qr-code');
        $manager->enable('qr-code');
        app(ModuleBootstrapper::class)->bootstrap();
        app('router')->getRoutes()->refreshNameLookups();
    }
}
