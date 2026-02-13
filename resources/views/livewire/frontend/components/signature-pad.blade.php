<div
    wire:ignore
    x-data="signaturePad({
        id: '{{ $canvasId }}',
        disabled: {{ $disabled ? 'true' : 'false' }}
    })"
    x-init="$nextTick(() => init())"
    class="signature-pad-container"
    id="{{ $id }}"
>

    <div class="signature-pad-wrapper">
        <canvas
            id="{{ $canvasId }}"
            class="signature-canvas"
            width="600"
            height="200"
            @mousedown="startDrawing($event)"
            @mousemove="draw($event)"
            @mouseup="stopDrawing"
            @mouseleave="stopDrawing"
            @touchstart.prevent="startDrawingTouch($event)"
            @touchmove.prevent="drawTouch($event)"
            @touchend="stopDrawing"
        ></canvas>

        @if($signatureData)
            <img
                src="{{ $signatureData }}"
                alt="Signature"
                class="signature-preview"
                style="display:none;"
            />
        @endif
    </div>

    <div class="signature-pad-actions">
        <x-mary-button
            label="{{ $clearLabel }}"
            icon="o-trash"
            class="btn-outline btn-sm"
            wire:click="clear"
            :disabled="$disabled"
        />
    </div>

    <input
        type="hidden"
        wire:model.live="signatureData"
        id="{{ $canvasId }}-input"
    />

   

    <style>
        .signature-pad-container {
            width: 100%;
        }

        .signature-pad-wrapper {
            border: 2px dashed #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .signature-canvas {
            width: 100%;
            height: 200px;
            cursor: crosshair;
            display: block;
        }

        .signature-pad-actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
        }
    </style>

</div>
