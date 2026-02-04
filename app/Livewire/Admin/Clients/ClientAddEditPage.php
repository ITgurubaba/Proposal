<?php

namespace App\Livewire\Admin\Clients;

use App\Models\Client;
use App\Models\ClientPerson;
use App\Models\Country;
use Illuminate\Support\Arr;
use Livewire\Component;
use Mary\Traits\Toast;

class ClientAddEditPage extends Component
{
    use Toast;

    public $client_id;
    public $request = [];
    public $persons = [];
    public $countries = [];
    public string $activeTab = 'contact';

    protected function validationAttributes(): array
    {
        return [
            'request.customer_type' => 'Customer Type',
            'request.company_registration_number' => 'Company Registration Number',
            'request.company_name' => 'Company Name',
            'request.address_line_1' => 'Address Line 1',
            'request.address_line_2' => 'Address Line 2',
            'request.address_line_3' => 'Address Line 3',
            'request.city' => 'City',
            'request.zip_code' => 'Zip Code',
            'request.country' => 'Country',
            'persons.*.first_name' => 'First Name',
            'persons.*.last_name' => 'Last Name',
            'persons.*.email' => 'Email',
            'persons.*.phone' => 'Phone',
        ];
    }

    protected function rules(): array
    {
        return [
            // CLIENT
            'request.customer_type' => 'required|string|max:255',
            'request.company_registration_number' => 'nullable|string|max:255',
            'request.company_name' => 'required|string|max:255',

            // 🔴 ADDRESS MANDATORY
            'request.address_line_1' => 'required|string|max:255',
            'request.city'           => 'required|string|max:255',
            'request.zip_code'       => 'required|string|max:50',
            'request.country'        => 'required|string|max:255',

            // optional address parts
            'request.address_line_2' => 'nullable|string|max:255',
            'request.address_line_3' => 'nullable|string|max:255',

            // 🔴 PRIMARY PERSON (FIRST PERSON) MANDATORY
            'persons.0.first_name' => 'required|string|max:255',
            'persons.0.last_name'  => 'required|string|max:255',
            'persons.0.email'      => 'required|email|max:255',
            'persons.0.phone'      => 'required|string|max:50',

            // 🟡 OTHER PERSONS OPTIONAL
            'persons.*.first_name' => 'nullable|string|max:255',
            'persons.*.last_name'  => 'nullable|string|max:255',
            'persons.*.email'      => 'nullable|email|max:255',
            'persons.*.phone'      => 'nullable|string|max:50',
        ];
    }


    public function mount()
    {
        if (checkData($this->client_id)) {
            $check = Client::find($this->client_id);
            if ($check) {
                $this->EditRequest($check);
            } else {
                return redirect()->route('admin::company.clients.list')->with('error', 'Invalid client id');
            }
        } else {
            $this->NewRequest();
        }

        $this->countries = Country::where('status', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($country) {
                return [
                    'value' => $country->name,
                    'label' => $country->name,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.clients.client-add-edit-page');
    }


    public function submitForm()
    {
        if ($this->client_id) {
            $this->save();
        } else {
            $this->submit();
        }
    }


    public function submit()
    {
        if (count($this->persons) < 1) {
            $this->addError('persons', 'At least one primary person is required.');
            $this->activeTab = 'persons';
            return;
        }

        $this->validate();
        $this->createClient($this->request);
    }

    public function save()
    {
        if (count($this->persons) < 1) {
            $this->addError('persons', 'At least one primary person is required.');
            $this->activeTab = 'persons';
            return;
        }

        $this->validate();
        $this->updateClient($this->request);
    }



    private function createClient($data = []): void
    {
        try {
            $client = Client::create($data);
            $this->client_id = $client->id;

            // Save persons
            $this->savePersons($client);

            $this->dispatch(
                'SweetMessage',
                type: 'success',
                title: 'New Client',
                message: 'Client added successfully',
                url: route('admin::company.clients.list'),
            );
        } catch (\Exception $e) {
            $this->error('Error!', $e->getMessage());
        }
    }



    private function updateClient($data = []): void
    {
        try {
            $client = Client::find($this->client_id);
            $client->fill($data);
            $client->save();

            // Save persons
            $this->savePersons($client);

            $this->dispatch(
                'SweetMessage',
                type: 'success',
                title: 'Edit Client',
                message: 'Client updated successfully',
                url: route('admin::company.clients.list'),
            );
        } catch (\Exception $e) {
            $this->error('Error!', $e->getMessage());
        }
    }

    private function savePersons($client): void
    {
        // Delete existing persons
        $client->persons()->delete();

        // Create new persons
        foreach ($this->persons as $person) {
            if (!empty($person['first_name']) || !empty($person['last_name'])) {
                ClientPerson::create([
                    'client_id' => $client->id,
                    'first_name' => $person['first_name'] ?? null,
                    'last_name' => $person['last_name'] ?? null,
                    'email' => $person['email'] ?? null,
                    'phone' => $person['phone'] ?? null,
                ]);
            }
        }
    }

    private function NewRequest(): void
    {
        $this->request = [
            'customer_type' => '',
            'company_registration_number' => '',
            'company_name' => '',
            'address_line_1' => '',
            'address_line_2' => '',
            'address_line_3' => '',
            'city' => '',
            'zip_code' => '',
            'country' => '',
        ];

        $this->persons = [
            $this->makePerson(),
            $this->makePerson(),
        ];
    }

    private function EditRequest($client): void
    {
        $this->request = [
            'customer_type' => $client->customer_type ?? '',
            'company_registration_number' => $client->company_registration_number ?? '',
            'company_name' => $client->company_name ?? '',
            'address_line_1' => $client->address_line_1 ?? '',
            'address_line_2' => $client->address_line_2 ?? '',
            'address_line_3' => $client->address_line_3 ?? '',
            'city' => $client->city ?? '',
            'zip_code' => $client->zip_code ?? '',
            'country' => $client->country ?? '',
        ];

        $this->persons = $client->persons->map(function ($person) {
            return [
                'id' => uniqid(), // important
                'first_name' => $person->first_name ?? '',
                'last_name' => $person->last_name ?? '',
                'email' => $person->email ?? '',
                'phone' => $person->phone ?? '',
            ];
        })->toArray();

        while (count($this->persons) < 2) {
            $this->persons[] = $this->makePerson();
        }
    }

    private function makePerson(): array
    {
        return [
            'id' => uniqid(),
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
        ];
    }

    public function addNewPerson(): void
    {
        $this->persons[] = $this->makePerson();
    }


    public function removePerson($index = null): void
    {
        if (count($this->persons) <= 1) {
            return; // 🚫 last primary person cannot be removed
        }

        if (isset($this->persons[$index])) {
            unset($this->persons[$index]);
            $this->persons = array_values($this->persons);
        }
    }



    public function getCustomerTypesProperty(): array
    {
        return [
            ['value' => 'limited_company', 'label' => 'Ltd / Limited Company'],
            ['value' => 'limited_by_share', 'label' => 'Limited By Share'],
            ['value' => 'limited_by_guarantee', 'label' => 'Limited By Guarantee'],
            ['value' => 'cic', 'label' => 'Ltd / Limited Company - Community Interest Company (CIC)'],
            ['value' => 'partnership', 'label' => 'Partnership'],
            ['value' => 'llp', 'label' => 'LLP'],
            ['value' => 'individual', 'label' => 'Individual'],
        ];
    }
}
