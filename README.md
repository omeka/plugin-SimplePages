Simple Pages (plugin for Omeka)
===============================

[Simple Pages] is a plugin for [Omeka] that allows administrators to create
simple web pages for their public site.

Keep it simple. Use this plugin to add an “About” page or other basic content to
your site.


Installation
------------

Uncompress files and rename plugin folder "SimplePages".

Then install it like any other Omeka plugin and follow the config instructions.


Usage
-----

Simply add and edit pages via the main menu.

See the [dedicated page] for more information.


Shortcodes
----------

The shortcode allows to add blocks, lists or navigation into a page.

The general syntax is `[simple_pages key=value]`, where keys can be:

- slug
  The slug of the selected page to display.

- id
  The id of the selected page to display.

- slugs
  The slugs of the selected pages to display.

- ids
  The ids of the selected pages to display.

Generally, only one of these four keys are used.

- output
  Select the type of content to return. Can be "block" (default, the full
  content), "text" (same as block but without title), "link" (list of links to
  simple pages), "title" (list of title of simple pages), or "navigation" (the
  hierarchical list of pages under a root page).

For lists, two other arguments are available:

- sort
  Can be "alpha" or "order": the method to sort pages. If not set, slugs will be
  sorted by the order of the `slugs` argument, and `ids` by the default order
  ("order").

- num
  Allows to limit the number of pages to display (10 by default). This is used
  only when there are no limitation (no keys or a range of ids).

For links, a specific argument is available:

- titles
  It allows to choose alternative titles (generally shorter) for links. This is
  usefull when shortcodes are used to build alternative menus in blocks. They
  should be separated with ";" and this character shouldn't appear in the title.

All arguments are optional.


Warning
-------

Use it at your own risk.

It's always recommended to backup your files and database regularly so you can
roll back if needed.


Troubleshooting
---------------

See online issues on the [Simple Pages issues] page on GitHub.


License
-------

This plugin is published under [GNU/GPL].

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.


Contact
-------

Current maintainers:

* Roy Rosenzweig Center for History and New Media


Copyright
---------

* Copyright Roy Rosenzweig Center for History and New Media, 2010-2014
* Copyright Daniel Berthereau, 2014 (improvements, see [Daniel-KM])


[Omeka]: https://omeka.org
[Simple Pages]: http://omeka.org/codex/Plugins/SimplePages
[dedicated page]: http://omeka.org/codex/Plugins/SimplePages_2.0
[Simple Pages issues]: http://omeka.org/forums/forum/plugins
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html "GNU/GPL v3"
[Daniel-KM]: https://github.com/Daniel-KM "Daniel Berthereau"
