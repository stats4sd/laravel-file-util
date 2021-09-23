<?php

namespace Stats4sd\FileUtil\Http\Controllers\Operations;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

trait ExportOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupExportRoutes($segment, $routeName, $controller)
    {
        logger('ExportOperation.setupExportRoutes() starts...');

        Route::get($segment . '/export', [
            'as' => $routeName . '.export',
            'uses' => $controller . '@export',
            'operation' => 'export',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupExportDefaults()
    {
        logger('ExportOperation.setupExportDefaults() starts...');

        $this->crud->allowAccess('export');

        $this->crud->operation('export', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('top', 'export', 'view', 'crud::buttons.export');
        });
    }

    /**
     * Export All rows to Excel
     *
     * @return \Illuminate\View\View
     */
    public function export()
    {
        logger('ExportOperation.export() starts...');

        $this->crud->hasAccessOrFail('export');
        $exporter = $this->crud->get('export.exporter');

        if (! $exporter) {
            return response("Exporter Class not found - please check the exporter is properly setup for this page", 500);
        }

        return Excel::download(new $exporter(), $this->crud->entity_name_plural." - ".Carbon::now()->format('Ymd_His').".xlsx");
    }
}
