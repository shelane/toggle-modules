Acquia BLT Toggle Modules integration
====

This is an [Acquia BLT](https://github.com/acquia/blt) plugin providing a process for toggling modules on a per 
environment basis.

This plugin is **community-created** and **community-supported**. Acquia does not provide any direct support for this 
software or provide any warranty as to its stability.

## Installation and usage

To use this plugin, you must already have a Drupal project using BLT 13.

1. Add this plugin to your project using composer:

`composer require shelane/toggle-modules`

2. Initialize the toggle settings for your project:

`blt recipes:config:init:toggle-modules`

3. Update your `blt.yml` file with the list of modules you wish to enable to disable in each environment.

The command is registered as post-command hooks for the blt commands `drupal:install` and `drupal:config:import`. You 
can also call the command manually by calling `blt drupal:toggle:modules`.

# License

Copyright (C) 2021 Acquia, Inc.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public 
License version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied 
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
