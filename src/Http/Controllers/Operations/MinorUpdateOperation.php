<?php

namespace Backpack\EditableColumns\Http\Controllers\Operations;

use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait MinorUpdateOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param  string  $segment  Name of the current entity (singular). Used as first URL segment.
     * @param  string  $routeName  Prefix of the route name.
     * @param  string  $controller  Name of the current CrudController.
     */
    protected function setupMinorUpdateRoutes($segment, $routeName, $controller)
    {
        Route::post($segment . '/minor-update', [
            'as' => $routeName . '.saveMinorUpdate',
            'uses' => $controller . '@saveMinorUpdate',
            'operation' => 'MinorUpdate',
        ]);
    }

    /**
     * Setup operation default settings.
     */
    protected function setupMinorUpdateDefaults()
    {
        $this->crud->operation(['list', 'show'], function () {
            Widget::add()->type('view')->view('backpack.editable-columns::miscellaneous.minor_update_client_logic');
        });
    }

    /**
     * Setup operation default form validations.
     */
    public function saveMinorUpdateFormValidation()
    {
        $input = request()->only(['id', 'attribute', 'value', 'row']);

        // Validate attribute is fillable
        if (! $this->crud->model->isFillable($input['attribute'])) {
            throw ValidationException::withMessages([
                $input['attribute'] => [__('backpack.editable-columns::minor_update.error_fillable', $input)],
            ]);
        }

        // Form Validation
        $formRequest = $this->crud->getFormRequest();
        if ($formRequest) {
            $rules = (new $formRequest())->rules();

            $validator = Validator::make($input, [
                'value' => $rules[$input['attribute']] ?? '',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }

    /**
     * Get table content for the specified row.
     */
    protected function getMinorUpdateRow()
    {
        $entry = $this->crud->getModel()->find(request('id'));

        $this->crud->setCurrentOperation('list');
        $this->setupConfigurationForCurrentOperation();

        return $this->crud->getEntriesAsJsonForDatatables([$entry->withFakes()], 1, 1)['data'][0] ?? null;
    }

    /**
     * Setup operation default settings.
     */
    public function saveMinorUpdateEntry()
    {
        $entry = $this->crud->getModel()->find(request('id'));
        $fakeColumnName = request('fake');

        // store fake column
        if ($fakeColumnName) {
            $fakeColumnValue = $entry->{$fakeColumnName};

            // extras may be or may not be casted
            if (is_string($fakeColumnValue)) {
                $fakeColumnValue = json_decode($fakeColumnValue, true) ?? [];
                $fakeColumnValue[request('attribute')] = request('value');
                $fakeColumnValue = json_encode($fakeColumnValue);
            } else {
                $fakeColumnValue[request('attribute')] = request('value');
            }

            $entry->{$fakeColumnName} = $fakeColumnValue;

            return $entry->save();
        }

        // default save
        $entry->{request('attribute')} = request('value');

        return $entry->save();
    }

    /**
     * Setup operation default settings.
     */
    public function saveMinorUpdate()
    {
        // Validate request
        $this->saveMinorUpdateFormValidation();

        // Update entry
        $saved = $this->saveMinorUpdateEntry();

        return [
            'saved' => $saved,
            'row' => $this->getMinorUpdateRow(),
        ];
    }
}
