<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <div class="border rounded-lg shadow-sm bg-white dark:bg-gray-800 p-4">
            <iframe 
                class="w-full h-[600px] border-0" 
                :srcdoc="state"
                sandbox=""
            ></iframe>
        </div>
    </div>
</x-dynamic-component>
