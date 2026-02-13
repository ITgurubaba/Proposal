<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    protected $fillable = [
        'client_id',
        'total_price',
        'status',
        'signature_image',
        'signed_at',
        'signed_pdf_path',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'signed_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function services()
    {
        return $this->hasMany(ProposalService::class);
    }

    public function contents()
    {
        return $this->hasMany(ProposalContent::class)->orderBy('sort_order');
    }

    /**
     * Check if proposal is signed
     */
    public function isSigned(): bool
    {
        return !empty($this->signature_image) && !empty($this->signed_at);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'sent' => 'Sent',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'approved' => 'Approved',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'btn-warning',
            'sent' => 'btn-primary',
            'accepted' => 'btn-info',
            'rejected' => 'btn-error',
            'approved' => 'btn-success',
            default => 'btn-ghost',
        };
    }

    public function renderContent($content)
    {
        $html = $content->content;

        // ===============================
        // SERVICE FIELD PLACEHOLDERS
        // ===============================
        if ($content->service_id) {

            $proposalService = $this->services
                ->firstWhere('service_id', $content->service_id);

            if ($proposalService && !empty($proposalService->data['fields'])) {

                foreach ($proposalService->data['fields'] as $fieldId => $value) {

                    $field = \App\Models\ServiceField::find($fieldId);
                    if (!$field) continue;

                    $placeholder = '[' . $field->field_name . ']';

                    if (is_array($value)) {
                        $start = $value['start'] ?? '';
                        $end   = $value['end'] ?? '';
                        $value = trim($start . ' to ' . $end);
                    }

                    $html = str_replace($placeholder, $value, $html);
                }
            }
        }

        // ===============================
        // CLIENT PLACEHOLDERS
        // ===============================
        if ($this->client) {

            $person = $this->client->persons->first();

            $fullAddress = collect([
                $this->client->address_line_1,
                $this->client->address_line_2,
                $this->client->address_line_3,
                $this->client->city,
                $this->client->zip_code,
            ])->filter()->implode(', ');

            $replacements = [
                '[company_name]'                => $this->client->company_name ?? '',
                '[company_registration_number]' => $this->client->company_registration_number ?? '',
                '[client_name]'                 => $person ? trim($person->first_name . ' ' . $person->last_name) : '',
                '[client_email]'                => $person->email ?? '',
                '[client_phone]'                => $person->phone ?? '',
                '[company_address]'             => $fullAddress,
            ];

            $html = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $html
            );
        }

        return $html;
    }
}
