# Laravel File Utility Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stats4sd/fileutil.svg?style=flat-square)](https://packagist.org/packages/stats4sd/fileutil)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/stats4sd/fileutil/run-tests?label=tests)](https://github.com/stats4sd/fileutil/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/stats4sd/fileutil/Check%20&%20fix%20styling?label=code%20style)](https://github.com/stats4sd/fileutil/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/stats4sd/fileutil.svg?style=flat-square)](https://packagist.org/packages/stats4sd/fileutil)

## Installation

You can install the package via composer:

```bash
composer require stats4sd/laravel-file-util
```
## Usage

#### Pre-requisite:

The package has 3 main features, and you can use one or all of them in your project:

- A trait `HasUploadFields`: this is based heavily on the trait shipped with Laravel Backpack, but customised slightly to our needs. 
  - To use the trait, you need no other dependencies.
- 2 Operation classes, to be used with Laravel Backpack.
  - To use these operations, you should install the following extra dependencies:
    - `composer require backpack/crud`
    - `composer require maatwebsite/excel`

## 1. Exporting Data through a Laravel Backpack Crud panel

Both import and export operations use and require the [Laravel Excel](https://docs.laravel-excel.com/3.1) package. 

The ExportOperation lets you link an ModelExport class built with Laravel Excel to your Crud panel.

How to add an Excel Export:

1. Build your ModelExport class as described [here](https://docs.laravel-excel.com/3.1/exports/)
 - To test this operation class, start with the most basic version of an export (e.g. implement FromCollection and just get some items from your CRUD's model. You can always add things later to customise your export.

```php
<?php

namespace App\Exports;

use App\Models\Tag;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TagsExport implements FromCollection, WithTitle, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tag::select(
            'id',
            'name',
            'slug',
            'files',
            'created_at',
            'updated_at',
        )->get();
    }

    /**
    * @return string
    */
    public function title(): string
    {
        return 'Tag';
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'slug',
            'files',
            'created_at',
            'updated_at',
        ];
    }
}
```

2. Use your ModelExport class in your CrudController: `use App\Exports\TagsExport;` 

3. Use the ExportOperation in your CrudController: `use \Stats4sd\FileUtil\Http\Controllers\Operations\ExportOperation;` 

4. Add the following in your CrudController: 
`use ExportOperation;` 

5. Add the following to your CrudController's setup() method:
`CRUD::set('export.exporter', YourModelExport::class);` (replace with the actual name of your ModelExport class)

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TagsExport;
use \Stats4sd\FileUtil\Http\Controllers\Operations\ExportOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TagCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TagCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use ExportOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Tag::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/tag');
        CRUD::setEntityNameStrings('tag', 'tags');
        CRUD::set('export.exporter', TagsExport::class);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns        
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setFromDb(); // fields
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

```

That's it! The operation adds an "Export" button to the 'top' stack in List view. Click the button to download the File generated from your ModelExport class.

**Notes**:
 - The default file name is the Crud entity_plural_name, with a date-time string appended to the end.
 - The default format is .xlsx
 - To override, add an export() method to your crud controller. 

Everything about the exported file is defined by the ModelExport class. You can customise it using any of the features of [Laravel Excel](https://docs.laravel-excel.com/3.1) as you would if you were using the package anywhere else in Laravel. All this Operation does is make it easier to quickly link an ModelExport class to a Crud panel. 

## 2. Importing Data through a Laravel Backpack Crud panel
The ImportOperation lets you link an ModelImport class built with Laravel Excel to your Crud panel.

How to add:

1. Build your ModelImport class as described [here](https://docs.laravel-excel.com/3.1/imports/)

```php
<?php

namespace App\Imports;

use App\Models\Tag;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TagsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Tags|null
     */
    public function model(array $row)
    {
        return new Tag([
           'name'       => $row['name'],
           'slug'       => $row['slug'],
           'files'      => $row['files'],
        ]);
    }
}
```

2. Build your ImportRequest class as below

```php
<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'importFile' => 'required|file|mimes:xls,xlsx|max:24000',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
```

3. Use your ModelImport class in your CrudController: `use App\Imports\TagsImport;` 

4. Use the ImportOperation in your CrudController: `use \Stats4sd\FileUtil\Http\Controllers\Operations\ImportOperation;` 

5. Add the following in your CrudController: 
`use ImportOperation;` 

6. Add the following to your CrudController's setup() method:
`CRUD::set('import.importer', YourModelImport::class);` (replace with the actual name of your ModelImport class)


```php
<?php

namespace App\Http\Controllers\Admin;

use App\Imports\TagsImport;
use \Stats4sd\FileUtil\Http\Controllers\Operations\ImportOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TagCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TagCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use ImportOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Tag::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/tag');
        CRUD::setEntityNameStrings('tag', 'tags');
        CRUD::set('import.importer', TagsImport::class);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns        
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setFromDb(); // fields
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
```

That's it! The operation adds an "Import" button to the 'top' stack in List view. Click this button to be taken to the Import View. This view contains a basic form with a file upload input and a submit button. Add an Excel file, submit, and the file will be processed by your ModelImport class.


**Notes**:
 - It's recommended to add validation to your importer. (See documentation page on [validating rows](https://docs.laravel-excel.com/3.1/imports/validation.html))
 - The import form has support for displaying errors returned from validation. If you use batch imports, you will see errors from the entire batch of rows at once, labeled with the correct row number. This is useful for seeing all errors at once in an Excel file, instead of going row-by-row and having to try importing the same file multiple times until it works with no validation errors. 

**TO DO: add examples of validation with both ToModel / BatchInserts AND ToCollection concerns**

## 3. File Upload + File Download Operations

1. Use FileController class in your routes\web.php: `use \Stats4sd\FileUtil\Http\Controllers\FileController;`

2. Add below section in your routes\web.php

```
Route::group([
    'middleware' => ['web']
], function () {
    Route::get('image/{path}', [FileController::class, 'getImage'])->where('path', '.*')->name('image.get');
    Route::get('download/{path}/{disk?}', [FileController::class, 'download'])->where('path', '.*')->name('file.download');
});
```

3. In config\filesystem.php, add a line to "disks" \ "local" section
`'url' => env('APP_URL').'/download/',`

4. Use HasUploadFields class in your Model class: `use \Stats4sd\FileUtil\Models\Traits\HasUploadFields;`

5. Use trait HasUploadFields class in your Model class: `use CrudTrait, HasUploadFields;`

6. Add below function to your Model class, it will save uploaded files into folder "storage\app\site". File name will be stored in Model class column "files"

```
    public function setFilesAttribute($value)
    {
        $attribute_name = "files";
        $disk = "local";
        $destination_path = "site";

        $this->uploadMultipleFilesWithNames($value, $attribute_name, $disk, $destination_path);
    }
```

7. Add the following to your CrudController's setupCreateOperation() method:
`CRUD::field('files')->type('upload_multiple')->disk('local')->upload(true)->label('Files or charts for the site')->hint('If you have charts or other files, please upload them here');`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dan Tang](https://github.com/stats4sd)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
