<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'content_type',
        'source_content_id',
        'title',
        'content',
        'service_id',
        'service_item_id',
        'sort_order',
    ];

    /**
     * Get the proposal that owns the content.
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the service that owns the content (for service content type).
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the service item that owns the content (for service item content type).
     */
    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    /**
     * Get the source service content.
     */
    public function sourceServiceContent(): BelongsTo
    {
        return $this->belongsTo(ServiceContent::class, 'source_content_id');
    }

    /**
     * Get the source other content.
     */
    public function sourceOtherContent(): BelongsTo
    {
        return $this->belongsTo(OtherContent::class, 'source_content_id');
    }
}
