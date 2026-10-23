<?php

declare(strict_types=1);

namespace Modules\QrCode\Http\Controllers;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QrCodeController
{
    public function index(Request $request): View
    {
        $text = $this->text($request);

        return view('qr-code::index', [
            'text' => $text,
            'image' => 'data:image/svg+xml;base64,'.base64_encode($this->render($text)),
            'runtimeClass' => Writer::class,
        ]);
    }

    public function svg(Request $request): Response
    {
        return response($this->render($this->text($request)), 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="qr-code.svg"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function text(Request $request): string
    {
        $validated = $request->validate([
            'text' => ['sometimes', 'required', 'string', 'max:512'],
        ]);

        return $validated['text'] ?? 'https://frolax.com';
    }

    private function render(string $text): string
    {
        $renderer = new ImageRenderer(new RendererStyle(256), new SvgImageBackEnd);

        return (new Writer($renderer))->writeString($text, 'UTF-8');
    }
}
