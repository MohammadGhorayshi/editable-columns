<?php

namespace Backpack\EditableColumns;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'backpack';
    protected $packageName = 'editable-columns';
    protected $commands = [];
}
