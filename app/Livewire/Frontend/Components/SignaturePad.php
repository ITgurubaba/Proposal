<?php

namespace App\Livewire\Frontend\Components;

use Livewire\Component;

class SignaturePad extends Component
{
    public string $id = 'signature-pad';

    public string $canvasId = 'signature-canvas';

    public ?string $signatureData = null;

    public bool $disabled = false;

    public string $clearLabel = 'Clear';

    public string $saveLabel = 'Save Signature';

    protected $listeners = [
        'clearSignature' => 'clear',
        'disable' => 'disable',
        'enable' => 'enable',
    ];

    public function render()
    {
        return view('livewire.frontend.components.signature-pad');
    }

    /**
     * Clear the signature
     */
    public function clear(): void
    {
        $this->signatureData = null;
        $this->dispatch('signatureCleared');
    }

    /**
     * Disable the signature pad
     */
    public function disable(): void
    {
        $this->disabled = true;
    }

    /**
     * Enable the signature pad
     */
    public function enable(): void
    {
        $this->disabled = false;
    }

    /**
     * Handle signature data from JavaScript
     */
    public function updatedSignatureData(string $value): void
    {
        if (!empty($value)) {
            $this->dispatch('signatureSaved', signature: $value);
        }
    }
}
