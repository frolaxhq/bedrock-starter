<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Code · Bedrock module</title>
    <style>
        :root { color-scheme: light; font-family: system-ui, sans-serif; color: #172524; background: #eef4f0; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 48px 20px; }
        main { max-width: 920px; margin: auto; }
        .badge { display: inline-block; border: 1px solid #b5d5c4; border-radius: 99px; padding: 6px 12px; font-size: 13px; color: #176443; }
        h1 { font-size: clamp(32px, 5vw, 48px); letter-spacing: -2px; margin: 20px 0 8px; }
        .intro { color: #536963; line-height: 1.6; max-width: 620px; }
        .card { display: grid; grid-template-columns: 1fr 300px; gap: 36px; padding: 32px; background: #fff; border-radius: 20px; margin-top: 28px; border: 1px solid #dce8e0; }
        label { display: block; font-weight: 600; margin-bottom: 12px; }
        textarea { width: 100%; min-height: 150px; border: 1px solid #b5c9bd; border-radius: 10px; padding: 14px; font: inherit; resize: vertical; }
        button, .download { display: inline-block; background: #145c42; color: white; border: 0; border-radius: 9px; padding: 12px 18px; font: inherit; text-decoration: none; cursor: pointer; margin-top: 16px; }
        .preview { text-align: center; }
        img { width: 256px; height: 256px; max-width: 100%; }
        .download { background: #eef4f0; color: #145c42; }
        .error { color: #b42318; }
        .runtime { margin-top: 24px; padding: 20px; border: 1px dashed #b5c9bd; border-radius: 12px; font-size: 13px; line-height: 1.8; }
        code { overflow-wrap: anywhere; }
        @media (max-width: 700px) { .card { grid-template-columns: 1fr; padding: 24px; } }
    </style>
</head>
<body>
<main>
    <span class="badge">Bedrock · independently packaged module</span>
    <h1>Your text. One QR code.</h1>
    <p class="intro">A real module with its own Composer library, isolated PHP runtime and downloadable SVG. No frontend build or host Composer dependency required.</p>
    <section class="card">
        <form method="GET" action="{{ route('qr-code.index') }}">
            <label for="text">URL or text</label>
            <textarea id="text" name="text" maxlength="512" required>{{ old('text', $text) }}</textarea>
            @error('text')
                <p class="error">{{ $message }}</p>
            @enderror
            <button type="submit">Generate QR code</button>
        </form>
        <div class="preview">
            <img src="{{ $image }}" alt="QR code for the entered text" width="256" height="256">
            <a class="download" href="{{ route('qr-code.svg', ['text' => $text]) }}">Download SVG</a>
        </div>
    </section>
    <section class="runtime" aria-label="Dependency isolation proof">
        <strong>Private library:</strong> bacon/bacon-qr-code<br>
        <strong>Actual runtime class:</strong> <code>{{ $runtimeClass }}</code><br>
        The prefixed class belongs to this module; the host does not install this SDK.
    </section>
</main>
</body>
</html>
