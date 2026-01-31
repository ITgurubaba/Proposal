<div>
    <div x-on:open-notification-drawer.window="$wire.notificationDrawer = true">
        <x-mary-drawer wire:model="notificationDrawer" class="w-11/12 lg:w-1/3" right>
            <div>
                <x-mary-header subtitle="Showing the latest notifications" >

                    <x-slot:title class="text-4xl">
                        Notifications
                    </x-slot:title>

                    <x-slot:actions>
                        <x-mary-button label="Close" @click="$wire.notificationDrawer = false" />
                    </x-slot:actions>
                </x-mary-header>
                <ul class="space-y-2">
                    <!-- Example notification items -->
                    <li class="p-2 border-b">Notification 1: You have a new message.</li>
                    <li class="p-2 border-b">Notification 2: Your order has been shipped.</li>
                    <li class="p-2 border-b">Notification 3: New comment on your post.</li>
                    <!-- Add more notifications as needed -->
                </ul>
            </div>
        </x-mary-drawer>
    </div>
</div>
