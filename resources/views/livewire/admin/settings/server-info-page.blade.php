<div>
    <x-mary-header subtitle="Here you can see the platform details" >

        <x-slot:title class="text-4xl">
            Server Information
        </x-slot:title>

        <x-slot:actions>
        </x-slot:actions>
    </x-mary-header>

    <div class="flex flex-wrap -mx-4">
        <div class="w-full md:w-1/3 px-4">
            <x-mary-card class="p-4 shadow border">
                <table class="w-full text-sm">
                    <thead>
                    <tr>
                        <th colspan="3" class="font-bold pb-5">Software</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-display"></i>
                        </td>
                        <td class="w-24">OS</td>
                        <td>{{ Arr::get($data, 'server.software.os') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-info-circle"></i>
                        </td>
                        <td class="w-24">Version</td>
                        <td>{{ Arr::get($data, 'server.software.distro') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-cpu"></i>
                        </td>
                        <td class="w-24">Kernel</td>
                        <td>{{ Arr::get($data, 'server.software.kernel') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-collection"></i>
                        </td>
                        <td class="w-24">Architecture</td>
                        <td>{{ Arr::get($data, 'server.software.arc') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-hdd-network"></i>
                        </td>
                        <td class="w-24">Web Server</td>
                        <td>{{ Arr::get($data, 'server.software.webserver') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-info"></i>
                        </td>
                        <td class="w-24">Version</td>
                        <td>{{ Arr::get($data, 'server.software.php') ?? '' }}</td>
                    </tr>
                    </tbody>
                </table>
            </x-mary-card>
        </div>
        <div class="w-full md:w-1/3 px-4">
            <x-mary-card class="p-4 shadow border">
                <table class="w-full text-sm">
                    <thead>
                    <tr>
                        <th colspan="3" class="font-bold pb-5">Hardware</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-cpu-fill"></i>
                        </td>
                        <td class="w-24">CPU</td>
                        <td>{{ Arr::get($data, 'server.hardware.cpu') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-kanban"></i>
                        </td>
                        <td class="w-24">Threads</td>
                        <td>{{ Arr::get($data, 'server.hardware.cpu_count') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-info-circle"></i>
                        </td>
                        <td class="w-24">Model</td>
                        <td>{{ Arr::get($data, 'server.hardware.model') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-badge-vr-fill"></i>
                        </td>
                        <td class="w-24">Virtualization</td>
                        <td>{{ Arr::get($data, 'server.hardware.virtualization') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-clock"></i>
                        </td>
                        <td class="w-24">Up Time</td>
                        <td>{{ Arr::get($data, 'server.uptime.uptime') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-calendar-check"></i>
                        </td>
                        <td class="w-24">Booted At</td>
                        <td>{{ Arr::get($data, 'server.uptime.booted_at') ?? '' }}</td>
                    </tr>
                    </tbody>
                </table>
            </x-mary-card>
        </div>
        <div class="w-full md:w-1/3 px-4">
            <x-mary-card class="p-4 shadow border">
                <table class="w-full text-sm">
                    <thead>
                    <tr>
                        <th colspan="3" class="font-bold pb-5">Database & Space</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-server"></i>
                        </td>
                        <td class="w-24">Driver</td>
                        <td>{{ Arr::get($data, 'database.driver') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-info-circle"></i>
                        </td>
                        <td class="w-24">Version</td>
                        <td>{{ Arr::get($data, 'database.version') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-hdd-fill"></i>
                        </td>
                        <td class="w-24">Disk Total</td>
                        <td>{{ Arr::get($data, 'server.hardware.disk.human_total') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-hdd"></i>
                        </td>
                        <td class="w-24">Disk Free</td>
                        <td>{{ Arr::get($data, 'server.hardware.disk.human_free') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-hdd-stack-fill"></i>
                        </td>
                        <td class="w-24">Ram Total</td>
                        <td>{{ Arr::get($data, 'server.hardware.ram.human_total') ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="w-6 text-center">
                            <i class="bi bi-hdd-stack"></i>
                        </td>
                        <td class="w-24">Ram Free</td>
                        <td>{{ Arr::get($data, 'server.hardware.ram.human_free') ?? '' }}</td>
                    </tr>
                    </tbody>
                </table>
            </x-mary-card>
        </div>
    </div>

</div>
