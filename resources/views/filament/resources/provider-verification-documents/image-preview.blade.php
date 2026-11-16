
@php
    $record = $getRecord();
    $viewerId = 'image-viewer-' . $record->getKey();
    $imageUrl = route('admin.provider-verification-documents.file', $record);
@endphp

<style>
    #{{ $viewerId }} {
        width: 100%;
        overflow: hidden;
        border: 1px solid #374151;
        border-radius: 10px;
        background: #1e1e1e;
        color: #f9fafb;
    }

    #{{ $viewerId }} .image-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 12px 16px;
        background: #292929;
        border-bottom: 1px solid #404040;
    }

    #{{ $viewerId }} .image-filename {
        min-width: 0;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 13px;
        font-weight: 500;
    }

    #{{ $viewerId }} .image-controls {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-shrink: 0;
    }

    #{{ $viewerId }} .image-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 34px;
        padding: 0 11px;
        border: 1px solid #555;
        border-radius: 6px;
        background: #3b3b3b;
        color: white;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background .15s ease;
    }

    #{{ $viewerId }} .image-btn:hover {
        background: #555;
    }

    #{{ $viewerId }} .image-btn:active {
        transform: scale(.96);
    }

    #{{ $viewerId }} .image-btn.reset {
        background: #2563eb;
        border-color: #2563eb;
    }

    #{{ $viewerId }} .image-btn.reset:hover {
        background: #1d4ed8;
    }

    #{{ $viewerId }} .zoom-label {
        min-width: 52px;
        text-align: center;
        font-size: 12px;
        color: #d1d5db;
        font-variant-numeric: tabular-nums;
    }

    #{{ $viewerId }} .image-area {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        width: 100%;
        height: 75vh;
        min-height: 450px;
        max-height: 900px;
        padding: 20px;
        overflow: auto;
        background: #202020;
    }

    #{{ $viewerId }} .preview-image {
        display: block;
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transform-origin: top center;
        transition: transform .15s ease;
    }

    @media (max-width: 600px) {
        #{{ $viewerId }} .image-toolbar {
            align-items: flex-start;
            flex-direction: column;
            padding: 12px;
        }

        #{{ $viewerId }} .image-filename {
            width: 100%;
        }

        #{{ $viewerId }} .image-area {
            height: 65vh;
            min-height: 350px;
            padding: 12px;
        }

        #{{ $viewerId }} .image-controls {
            width: 100%;
        }
    }
</style>

<div id="{{ $viewerId }}">
    <div class="image-toolbar">
        <div class="image-filename"
             title="{{ $record->original_filename }}">
            {{ $record->original_filename }}
        </div>

        <div class="image-controls">
            <button type="button" class="image-btn" data-zoom-out
                aria-label="Perkecil gambar">−</button>

            <span class="zoom-label" data-zoom-label>100%</span>

            <button type="button" class="image-btn" data-zoom-in
                aria-label="Perbesar gambar">+</button>

            <button type="button" class="image-btn reset" data-reset>
                Reset
            </button>
        </div>
    </div>

    <div class="image-area" data-image-area>
        <img
            src="{{ $imageUrl }}"
            alt="Preview {{ $record->original_filename }}"
            class="preview-image"
            data-preview-image
        >
    </div>
</div>

<script>
(() => {
    const viewer = document.getElementById(@json($viewerId));

    if (!viewer || viewer.dataset.initialized) return;

    viewer.dataset.initialized = 'true';

    const image = viewer.querySelector('[data-preview-image]');
    const label = viewer.querySelector('[data-zoom-label]');
    const area = viewer.querySelector('[data-image-area]');

    let scale = 1;

    function updateZoom() {
        image.style.transform = `scale(${scale})`;
        label.textContent = `${Math.round(scale * 100)}%`;
    }

    viewer.querySelector('[data-zoom-in]').addEventListener('click', () => {
        scale = Math.min(4, scale + 0.25);
        updateZoom();
    });

    viewer.querySelector('[data-zoom-out]').addEventListener('click', () => {
        scale = Math.max(0.25, scale - 0.25);
        updateZoom();
    });

    viewer.querySelector('[data-reset]').addEventListener('click', () => {
        scale = 1;
        updateZoom();
        area.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
    });
})();
</script>
