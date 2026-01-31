<div>
    <div {{ $attributes->merge() }}>
        <label class="pt-0 label label-text font-semibold">
            <span>
                {{ $label ??'' }}
            </span>
        </label>
        <!--[if ENDBLOCK]><![endif]-->

        <!-- PREFIX/SUFFIX/PREPEND/APPEND CONTAINER -->
        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <!-- PREFIX / PREPEND -->
        <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

        <div class="flex-1 relative">
            {{ $slot ??'' }}
        </div>
        @if(checkData($description))
            <div class="label-text-alt text-gray-400 p-1 pb-0">
                {{ $description }}
            </div>
        @endif
    </div>
</div>
