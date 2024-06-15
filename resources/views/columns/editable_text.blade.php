{{-- custom editable column --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    
    if(is_callable($column['value']) && $column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }
    
    if(is_array($column['value'])) {
        $column['value'] = json_encode($column['value']);
    }
    
    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 40;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['prefix'].Str::limit($column['value'], $column['limit'], '[...]').$column['suffix'];
    $column['underlined'] = $column['underlined'] ?? true;
    $column['select_on_click'] = $column['select_on_click'] ?? false;
    $column['save_on_focusout'] = $column['save_on_focusout'] ?? false;
    $column['min_width'] = $column['min_width'] ?? "120px";
    $column['type'] = $column['type'] ?? 'text';
    $column['fake'] = $column['fake'] ?? false;
    $column['store_in'] = $column['store_in'] ?? 'extras';
@endphp

<span
    data-column-type="text"
    data-column-editable="true"
    data-column-initial-value="{{ $column['value'] }}"
    data-column-limit="{{ $column['limit'] }}"
    data-column-events-registered="false"
    data-column-name="{{ $column['name'] }}"
    data-column-select-on-click="{{ $column['select_on_click'] }}"
    data-column-save-on-focusout="{{ $column['save_on_focusout'] }}"
    data-entry-id="{{ $entry->getKey() }}"
    data-route="{{ $column['route'] ?? url($crud->getRoute().'/minor-update') }}"
    data-text-color-unsaved="{{ $column['text_color_unsaved'] ?? '#869ab8' }}"
    data-on-error-text-color="{{ $column['on_error']['text_color'] ?? '#df4759' }}"
    data-on-error-text-color-duration="{{ $column['on_error']['text_color_duration'] ?? 0 }}"
    data-on-error-text-value-undo="{{ $column['on_error']['text_value_undo'] ?? false }}"
    data-on-success-text-color="{{ $column['on_success']['text_color'] ?? '#42ba96' }}"
    data-on-success-text-color-duration="{{ $column['on_success']['text_color_duration'] ?? 3000 }}"
    data-auto-update-row="{{ $column['auto_update_row'] ?? true }}"
    data-fake="{{ $column['fake'] ? $column['store_in'] : false }}"
    title="{{ __('backpack.editable-columns::minor_update.tooltip') }}">

    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
        <input
            type="{{ $column['type'] }}"
            name="{{ $column['name'] }}"
            value="{{ $column['value'] }}"
            data-focus="{{ $column['name'] }}"
            style="width: 100%; min-width: {{ $column['min_width'] }}; border: none; {{ $column['underlined'] ? "border-bottom: 1px dashed #abbcd5;" : "" }} background: none; text-overflow: ellipsis;"
            onfocus="registerMinorEditInputEvents(this, event)"
            onclick="(function(event){event.preventDefault(); event.stopPropagation();})(arguments[0]);return false;">
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>