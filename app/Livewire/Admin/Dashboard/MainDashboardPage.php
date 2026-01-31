<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\ContactMail;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;




class MainDashboardPage extends Component
{
    public $contactMsg = [];
    public $orderData = [];

    public float $totalSalesDelivered = 0;
    public float $totalCancelledSales = 0;
    public float $totalRefundedSales = 0;
    public float $totalsalesloss = 0;
    public float $monthlySales = 0;
    public float $monthlyLoss = 0;
    public int $selectedYear;
    public int $selectedMonth;
    public int $deliveredCount = 0;
    public int $cancelledCount = 0;
    public int $refundedCount = 0;
    public int $totalPendingOrders = 0;


    public array $chartOne = [
        'type' => 'pie',
        'data' => [
            'labels' => ['Mary', 'Joe', 'Ana'],
            'datasets' => [
                [
                    'label' => '# of Votes',
                    'data' => [12, 19, 3],
                ]
            ]
        ]
    ];

    public array $chartTwo = [
        'type' => 'bar',
        'data' => [
            'labels' => ['Mary', 'Joe', 'Ana'],
            'datasets' => [
                [
                    'label' => '# of Votes',
                    'data' => [12, 19, 3],
                ]
            ]
        ]
    ];

    public function mount()
    {

        $this->contactMsg = ContactMail::where('status', 0)->count();
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->month;

        $this->loadDashboardData();
    }

    public function updatedSelectedYear()
    {
        $this->loadDashboardData();
    }

    public function updatedSelectedMonth()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $startOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->endOfMonth();

        // Delivered Orders Count
       

        // Merge guest and user sales
        $monthlyTotals = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyTotals[] = ($salesByMonth[$m] ?? 0) + ($guestSalesByMonth[$m] ?? 0);
        }

        $this->chartTwo = [
            'type' => 'bar',
            'data' => [
                'labels' => [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ],
                'datasets' => [
                    [
                        'label' => 'Delivered Sales by Month',
                        'data' => $monthlyTotals,
                        'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                    ]
                ]
            ]
        ];
   

    }
    public function render()
    {
        return view('livewire.admin.dashboard.main-dashboard-page');
    }


    public function randomize()
    {
        \Arr::set($this->myChart, 'data.datasets.0.data', [fake()->randomNumber(2), fake()->randomNumber(2), fake()->randomNumber(2)]);
    }

    public function switch()
    {
        $type = $this->myChart['type'] == 'bar' ? 'pie' : 'bar';
        \Arr::set($this->myChart, 'type', $type);
    }
}
