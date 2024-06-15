{{-- custom editable column --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    
    if(is_callable($column['value']) && $column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if(is_callable($column['options']) && $column['options'] instanceof \Closure) {
        $column['options'] = $column['options']($entry);
    }

    $column['fake'] = $column['fake'] ?? false;
    $column['store_in'] = $column['store_in'] ?? 'extras';
@endphp

<span
    data-column-type="text"
    data-column-editable="true"
    data-column-initial-value="{{ $column['value'] }}"
    data-column-events-registered="false"
    data-column-name="{{ $column['name'] }}"
    data-column-save-on-focusout="{{ $column['save_on_focusout'] ?? true }}"
    data-column-save-on-change="{{ $column['save_on_change'] ?? true }}"
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
        <select
            name="{{ $column['name'] }}"
            data-focus="{{ $column['name'] }}"
            style="width: 100%; border: none; {{ ($column['underlined'] ?? true) ? "border-bottom: 1px dashed #abbcd5;" : "" }} background: none; text-overflow: ellipsis;"
            onfocus="registerMinorEditInputEvents(this, event)">
            @foreach($column['options'] as $value => $option)
                <option value="{{ $value }}" {{ $column['value'] == $value ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>