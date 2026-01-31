<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean up service fields with empty options
        $serviceFields = \App\Models\ServiceField::where('field_type', 'select')->get();

        foreach ($serviceFields as $field) {
            if (is_array($field->options)) {
                // Filter out empty options
                $cleanedOptions = array_filter($field->options, function($value) {
                    return !empty(trim($value));
                });

                // Re-index array
                $cleanedOptions = array_values($cleanedOptions);

                // Update the field
                if (empty($cleanedOptions)) {
                    // If no valid options, set to null
                    $field->options = null;
                } else {
                    $field->options = $cleanedOptions;
                }
                $field->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
