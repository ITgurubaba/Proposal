<?php

namespace App\Helpers\Traits;

trait WithMaryTable
{

    public ?string $search = "";

    public array $headers = [];

    public array $sortBy = [];
    public int $perPage = 10;
    public array $perPageOptions = [10, 25, 50, 100, 200, 500, 1000];

    public bool $filtersWithSession = false;
    public string $filterSessionKey = "";

    public function __construct()
    {
        $this->resetFilter();
    }

    private function resetFilter(): void
    {
        $this->sortBy = [
            'column'=>'id',
            'direction'=>'desc',
        ];
    }

    public function storeSessionFilters():void
    {
        if($this->filtersWithSession)
        {
            session()->put($this->filterSessionKey, [
                'search' => $this->search,
                'sortBy' => $this->sortBy,
                'perPage' => $this->perPage,
            ]);
        }
    }

    public function initializeSessionFilters():void
    {
        if($this->filtersWithSession)
        {
            if(session()->has($this->filterSessionKey))
            {
                $data = session()->get($this->filterSessionKey,[]);
                $this->search = $data["search"] ?? $this->search;
                $this->sortBy = $data["sortBy"] ??$this->sortBy;
                $this->perPage = $data["perPage"] ?? $this->perPage;
            }
        }
    }

    public function updatedSearch(): void
    {
        $this->storeSessionFilters();
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->storeSessionFilters();
    }

    public function updatedSortBy(): void
    {
        $this->storeSessionFilters();
    }

    public function initTableFilter($key = "admin.table.filters"):void
    {
        $this->filterSessionKey = $key;
        $this->filtersWithSession = true;
        $this->initializeSessionFilters();
    }

}
