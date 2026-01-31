<div
    x-data="{
        model: @entangle($attributes->wire('model')),
        phoneInput:null,
    }"
    x-init="
        phoneInput = intlTelInput($refs.tel, {
                initialCountry: '{{ $attributes->has('country')?$attributes->get('country'):'auto' }}',
                showSelectedDialCode:true,
                strictMode: true,
                showFlags:true,
                setNumber:model ??'',
                geoIpLookup: function(callback) {
                     fetch('https://ipapi.co/json')
                    .then(function(res) { return res.json(); })
                    .then(function(data) { callback(data.country_code); })
                    .catch(function() { callback(); });
                },
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/23.1.0/js/utils.js',

        })

        $($refs.tel).on('countrychange change',function (){
                // let countryData = phoneInput.getSelectedCountryData();
                //let isValid = phoneInput.isValidNumber();

                let number  = phoneInput.getNumber();
                model = number;
        })

        @if($attributes->has('data-change'))
            window.addEventListener('{{ $attributes->get('data-change') }}',()=>{
                     phoneInput.setNumber(model ??'');
            })
        @endif

    "
    wire:ignore
>
    <input x-ref="tel"
           id="international-phone"
         {{ $attributes->merge(['class' => 'intl-phone-input input input-primary w-full peer']) }}
    />
</div>

