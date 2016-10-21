# ElaoReadmeBundle

Provides route to navigate through markdown files

## Installation

```
composer require elao/readme-bundle
```

## Configuration

```
elao_readme:
    root_dir:       "%kernel.root_dir%/../"
    index:          README.md
    base_template:  ElaoReadmeBundle:Readme:base.html.twig
    index_template: ElaoReadmeBundle:Readme:index.html.twig
```

## Routing

```
_elao_readme:
    resource: "@ElaoReadmeBundle/Resources/config/routing.xml"
```
