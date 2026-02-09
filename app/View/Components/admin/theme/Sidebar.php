<?php

namespace App\View\Components\admin\theme;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Sidebar extends Component
{
    protected string $role;
    public array $sidebarMenu = [];
    public function __construct(Request $request)
    {
        $this->role = Auth::user()->userType();
        $this->sidebarMenu  = $this->generateSidebarMenu();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.theme.sidebar');
    }

    private function generateSidebarMenu(): array
    {
        return [
            'Dashboard' => [
                'name' => "Dashboard",
                'link' => route('admin::dashboard.main'),
                'icon' => 'o-home',
                'subMenu' => []
            ],


            'Client' => [
                'name' => "Client",
                'icon' => 'o-building-storefront',
                'subMenu' => [
                    'Manage Clients' => [
                        'name' => "Manage Clients",
                        'link' => route('admin::company.clients.list'),
                        'subMenu' => []
                    ],
                ]
            ],

            'Services' => [
                'name' => "Services",
                'icon' => 'o-briefcase', // ya koi aur icon
                'subMenu' => [
                    'Manage Services' => [
                        'name' => "Manage Services",
                        'link' => route('admin::services:list'),
                        'subMenu' => []
                    ],
                ]
            ],

            'Proposals' => [
                'name' => "Proposals",
                'icon' => 'o-document-text',
                'subMenu' => [
                    'Manage Proposals' => [
                        'name' => "Manage Proposals",
                        'link' => route('admin::proposals:list'),
                        'subMenu' => []
                    ],
                ]
            ],

                'Content Manager' => [
                'name' => "Content Manager",
                'icon' => 'o-document-text',
                'subMenu' => [
                    'Manage Service Content' => [
                        'name' => "Service Content",
                        'link' => route('admin::service-content:list'),
                        'subMenu' => []
                    ],
                    'Manage other Content' => [
                        'name' => "Manage other Content",
                        'link' => route('admin::other-content:list'),
                        'subMenu' => []
                    ],
                ]
            ],

            'Contact' => [
                'name' => "Contact",
                'link' => route('admin::contact:list'),
                'icon' => 'o-envelope',
                'subMenu' => []
            ],

            'Users' => [
                'name' => "Users",
                'icon' => 'o-users',
                'subMenu' => [
                    // 'Website' => [
                    //     'name' => "Website",
                    //     'link' => route('admin::users.website'),
                    //     'subMenu' => []
                    // ],
                    'Team' => [
                        'name' => "Team",
                        'link' => route('admin::users.team'),
                        'subMenu' => []
                    ],
                ]
            ],

            'Settings' => [
                'name' => "Settings",
                'icon' => 'm-cog-6-tooth',
                'subMenu' => [
                    'General' => [
                        'name' => "General",
                        'link' => route('admin::settings.general'),
                        'subMenu' => []
                    ],
                    'Mail' => [
                        'name' => "Mail",
                        'link' => route('admin::settings.mail'),
                        'subMenu' => []
                    ],
                    'Website' => [
                        'name' => "Platform",
                        'link' => route('admin::settings.website'),
                        'subMenu' => []
                    ],
                    'Server' => [
                        'name' => "Server",
                        'link' => route('admin::settings.server-logs'),
                        'subMenu' => [
                            'Logs' => [
                                'name' => "Logs",
                                'link' => route('admin::settings.server-logs'),
                                'subMenu' => [],
                                'no-navigate' => true,
                            ],
                            'Info' => [
                                'name' => "Info",
                                'link' => route('admin::settings.server-info'),
                                'subMenu' => []
                            ],
                        ]
                    ],
                ]
            ]
        ];
    }
}
