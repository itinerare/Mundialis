# Mundialis
Mundialis is a Laravel-based framework for managing story, character, and world information. It aims to reduce the friction of recording these by streamlining the process of template creation, use, and information formatting, while adding some specialized functionality.

- Demo Site: https://mundialis.dev
- Support Discord: https://discord.gg/mVqUzgQXMd

### Why Mundialis?
There are certainly preexisting solutions for keeping and organizing this kind of information, both standalone and service-based. However, I have yet to see one that did not more or less boil down to some implementation of a wiki, and rightly so; wiki software is very robust, meant to handle a great breadth and potentially depth of information, depending on the specific implementation and how it's used. 

The trouble arises from this last point. I have traditionally used a [MediaWiki](https://www.mediawiki.org/wiki/MediaWiki) instance for this purpose. While it is exceedingly powerful and meets most of my needs, it has a few notable drawbacks: namely, all non-basic formatting and organization need to be set up and manually maintained per page. Wiki software will generally provide tools to help with this, but there is only so much it can do on its own. Moreover, there are certain things a general-purpose app like that is simply not built to handle efficiently-- in this context, consider timelines or handling a lexicon for a conlang-- which may be prohibitively effort and/or time-intensive to manually document, format, etc.

Thus, this project was born out of a desire for a more efficient way to record world information-- one which takes some of the advantages of a wiki framework while taking a more specialized approach, bringing some minor automation to bear so that you can spend less time formatting and organizing your information, and more time creating. Moreover, since it's a self-contained app rather than a service, you can host it locally/on your own machine or on a server and manage your project's information on your own terms.

## Features
- Support for multiple users with read/write permissions handled via a lightweight rank system (user/editor/admin) (note that this is not designed for many people, just a small group, e.g. friends, collaborators)
- Page templates and template editing per-subject and per-category, including sub-categories, with optional selective change cascading from subject to categories and from categories to sub-categories (with optional recursive change cascading)-- that is, only new changes get applied, kinda like git but for page templates. They can be applied to all categories in a subject, all sub-categories of a category, or those and all *their* sub-categories ad infinitum. Forms for page editing are constructed from these templates, as well as pages themselves.
- Specialized functions for handling time: ability to set divisions of time (e.g. days, weeks) and set (and sort) larger portions of time (chronologies) for use as secondary categories for events/to provide more overarching order
- A specialized system for handling a project's lexicon, including configurable parts of speech and categories with an optional system for automatic conjugation/declension configurable per-category, as well as etymology tracking
- Pages organized by subject (people, places, things, etc) and category (including sub-categories). Includes some conveniences such as automatically generating table-of-contents based on template information and collapsible sections that load collapsed for a section if its contents are long
- Page editing built from the template system, with additional specialized fields for some subjects. Pages can also be moved or protected (set so only admins can edit)
- Wiki-like link parsing for easy/convenient linking of on-site pages within page content
- Page tagging-- either just for organization or for automated generation of navboxes-- including dedicated utility tags for keeping track of WIP, outdated, etc. pages
- Images and per-page image galleries; images can have multiple on- or off-site creators noted directly via the system, and can be associated with more than one page at once (with their validity for a given page can be tracked per-page)
- Version tracking for pages and images, including the ability to reset a page to a specific version and to restore deleted pages and images
- Relationship tracking for pages in the "people" subject, with bi-directional display and editing supporting configurable pre-defined as well as custom relationship types, and family member listing for pages with set family
- A timeline display for events, filterable by tag(s)
- Various special/utility pages for keeping track of pages via maintenance reports, etc. Includes things like un-/most-tagged pages, most and least revisions, pages with a given utility tag, and wanted pages as linked to within existing pages
- Per-user "watched pages"-- users are notified of changes to pages they have watched
- A random page button (just delightful, in my opinion)

## Setup
Important: For those who are not familiar with web development, please refer to the [Full Guide](https://code.itinerare.net/itinerare/Mundialis/wiki/Setup-Guide) for a much more detailed set of instructions!

### Obtain a copy of the code

```
$ git clone https://code.itinerare.net/itinerare/mundialis.git
```

### Configure .env in the directory

```
$ cp .env.example .env
```

Fill out .env as appropriate. The following are required:

- APP_NAME=(Your site's name, without spaces)
- APP_ENV=production
- APP_DEBUG=false
- APP_URL=
- CONTACT_ADDRESS=(Email address)

### Setting up

Install packages with composer:
```
$ composer install
```

Create the database (if not using mysql or mariaDB):
```
$ touch database/database.sqlite
```

Generate the application key and run database migrations:
```
$ php artisan key:generate
$ php artisan migrate
```

Perform general site setup:
```
$ php artisan setup-mundialis
```

## Contributing
Thank you for considering contributing to Mundialis! Please see the [Contribution Guide](CONTRIBUTING.md) for information on how best to contribute.

### Extending Mundialis
If you are interested in providing optional/plugin-type functionality for Mundialis, please contact me first and foremost; while I am open to developing plugin support and would rather do so before any are made, I will not be doing so until there is concrete interest in it.

## Contact
If you have any questions, please contact me via email at [mundialis@itinerare.net](emailto:mundialis@itinerare.net).
