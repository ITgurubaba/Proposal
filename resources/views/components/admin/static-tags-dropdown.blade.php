<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    
    <label class="text-sm font-medium text-gray-600">
        Insert Tag:
    </label>

    <select
        class="border rounded px-2 py-1 text-sm"
        onchange="insertServiceField(this.value); this.selectedIndex = 0;"
    >
        <option value="">-- Select Tag --</option>

        <option value="company_name">Company Name</option>
        <option value="company_registration_number">Company Registration Number</option>
        <option value="company_address">Company Address</option>
        <option value="client_name">Client Name</option>
        <option value="client_email">Client Email</option>
        <option value="client_phone">Client Phone</option>
    </select>

</div>
