<div>
   <div class="w-full lg:w-2/4 xl:w-2/6 lg:px-10 mx-auto mt-5 lg:mt-10 xl:mt-20">
       <x-mary-card title="Admin Login" class="border dark:border-gray-700" >
         <x-slot:menu>
             <x-mary-theme-toggle />
         </x-slot:menu>
           <x-mary-form wire:submit="login">

               <x-mary-input label="Email"
                             wire:model="request.email"
                             inline
               />
               <div x-data="{
                            type: 'password',
                            togglePassword: function() {
                                 this.type = (this.type === 'password' ? 'text' : 'password');
                            }
                        }">
                   <x-mary-input label="Password"
                                 inline
                                 x-bind:type="type"
                                 wire:model="request.password"
                   >
                       <x-slot name="suffix" class="bg-transparent border-none">
                           <a href="javascript:void(0)" x-on:click.prevent="togglePassword()">
                               <x-mary-icon name="o-eye" x-show="type == 'password'" />
                               <x-mary-icon name="o-eye-slash" x-show="type == 'text'" />
                           </a>
                       </x-slot>
                   </x-mary-input>
               </div>

               <x-mary-checkbox label="Remember me" wire:model="remember" />
               <x-mary-button label="Login" class="btn-primary" type="submit" spinner="login" />
           </x-mary-form>
       </x-mary-card>
   </div>
</div>
