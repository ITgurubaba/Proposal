<?php

namespace App\Livewire\Admin\Dashboard;
use App\Models\ContactMail;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderGuest;
use Livewire\Component;
use Illuminate\Support\Facades\DB;




class MainDashboardPage extends Component
{
  public $contactMsg = [];
  public $orderData = [];
 
  public float $totalSalesDelivered = 0;
  public float $totalCancelledSales = 0;
  public float $totalRefundedSales = 0;
   public float $totalsalesloss = 0;

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
        $orderSales = Order::where('status', Order::STATUS_DELIVERED)->sum('total');
        $guestOrderSales = OrderGuest::where('status', OrderGuest::STATUS_DELIVERED)->sum('total');
        $this->totalSalesDelivered = $orderSales + $guestOrderSales;
       
         // Cancelled
        $cancelledOrders = Order::where('status', Order::STATUS_CANCELLED)->sum('total');
        $cancelledGuestOrders = OrderGuest::where('status', Order::STATUS_CANCELLED)->sum('total');
        $this->totalCancelledSales = $cancelledOrders + $cancelledGuestOrders;
        
        // Refunded
        $refundedOrders = Order::where('status', Order::STATUS_REFUNDED)->sum('total');
        $refundedGuestOrders = OrderGuest::where('status', Order::STATUS_REFUNDED)->sum('total');
        $this->totalRefundedSales = $refundedOrders + $refundedGuestOrders;

        $this->totalsalesloss = $this->totalRefundedSales+$this->totalCancelledSales;
        // dd($this->totalsalesloss);
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
