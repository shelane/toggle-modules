Acquia BLT Toggle Modules integration
====

This is an [Acquia BLT](https://github.com/acquia/blt) plugin providing a process for toggling modules on a per 
environment basis.

This plugin is **community-created** and **community-supported**. Acquia does not provide any direct support for this 
software or provide any warranty as to its stability.

## Installation and usage

To use this plugin, you must already have a Drupal project using BLT 13.

In your project, require the plugin with Composer:

`composer require shelane/toggle-modules`

TODO
To by calling `recipes:multisite:init`, which is provided by this plugin:

`blt recipes:multisite:init`
TODO
This will copy a template site directory to create a new multisite installation. Make sure to commit all new and changed files to Git.

# License

Copyright (C) 2021 Acquia, Inc.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public 
License version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied 
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
