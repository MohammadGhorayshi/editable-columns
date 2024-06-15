{{-- custom editable column --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    
    if(is_callable($column['value']) && $column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }
    
    $column['underlined'] = $column['underlined'] ?? false;
    $column['fake'] = $column['fake'] ?? false;
    $column['store_in'] = $column['store_in'] ?? 'extras';
@endphp

<span
    data-column-type="checkbox"
    data-column-editable="true"
    data-column-initial-value="{{ $column['value'] ? 1 : 0 }}"
    data-column-events-registered="false"
    data-column-name="{{ $column['name'] }}"
    data-entry-id="{{ $entry->getKey() }}"
    data-route="{{ $column['route'] ?? url($crud->getRoute().'/minor-update') }}"
    data-on-error-status-color="{{ $column['on_error']['status_color'] ?? '#df4759' }}"
    data-on-error-status-color-duration="{{ $column['on_error']['status_color_duration'] ?? 0 }}"
    data-on-error-switch-value-undo="{{ $column['on_error']['switch_value_undo'] ?? true }}"
    data-on-success-status-color="{{ $column['on_success']['status_color'] ?? '#42ba96' }}"
    data-on-success-status-color-duration="{{ $column['on_success']['status_color_duration'] ?? 3000 }}"
    data-auto-update-row="{{ $column['auto_update_row'] ?? true }}"
    data-fake="{{ $column['fake'] ? $column['store_in'] : false }}"
    title="{{ __('backpack.editable-columns::minor_update.tooltip') }}"
    class="d-flex flex-column">

    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
        <div style="height: 18px;">
            <input
                type="checkbox"
                name="{{ $column['name'] }}"
                data-focus="{{ $column['name'] }}"
                {{ $column['value'] ? 'checked' : '' }}
                onchange="registerMinorEditInputEvents(this, event)">
            <span class="status" style="width: 10px; height: 10px; display: inline-block; border-radius: 50%; opacity: 0.8; margin: 2px;"></span>
        </div>
        <span style="width: 12px; {{ $column['underlined'] ? "border-top: 1px dashed #abbcd5;" : "" }}"></span>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>