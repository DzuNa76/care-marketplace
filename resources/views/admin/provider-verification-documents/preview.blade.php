
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Dokumen</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #111827;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px 24px;
            background: #1f2937;
            border-bottom: 1px solid #374151;
        }

        .toolbar-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border: none;
            border-radius: 7px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            background: #374151;
        }

        .btn:hover {
            background: #4b5563;
        }

        .btn-primary {
            background: #2563eb;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .preview-area {
            width: 100%;
            padding: 24px;
            display: flex;
            justify-content: center;
            overflow: auto;
        }

        #previewImage {
            display: block;
            width: auto;
            max-width: 100%;
            max-height: 75vh;
            object-fit: contain;
            transform-origin: top center;
            transition: transform 0.2s ease;
            border-radius: 8px;
        }

        @media (max-width: 600px) {
            .toolbar {
                padding: 12px;
            }

            .preview-area {
                padding: 12px;
            }

            .btn {
                padding: 9px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body>

    <header class="toolbar">
        <a href="{{ $backUrl }}" class="btn">
            &#8592; Kembali ke Detail Dokumen
        </a>

        <div class="toolbar-group">
            <button class="btn" onclick="zoom(-0.2)">−</button>
            <button class="btn" onclick="zoom(0.2)">+</button>
            <button class="btn btn-primary" onclick="resetZoom()">Reset</button>
        </div>
    </header>

    <main class="preview-area">
        <img
            id="previewImage"
            src="{{ $imageUrl }}"
            alt="Preview Dokumen"
        >
    </main>

    <script>
        let scale = 1;

        function zoom(amount) {
            scale = Math.min(4, Math.max(0.5, scale + amount));

            document.getElementById('previewImage').style.transform =
                `scale(${scale})`;
        }

        function resetZoom() {
            scale = 1;

            document.getElementById('previewImage').style.transform =
                'scale(1)';
        }
    </script>

</body>
</html>
