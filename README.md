Manage your Akeneo reference data
=====

This package is a composer plugin used to help you manage your Akeneo reference datas.

*__WARNING:__ This package is experimental, do not use it on production*

## Installation

Require the package in your installation :

```bash
composer require --dev "kiboko/akeneo-product-values-management=dev-master"
```

Additionally, you will have to define the following parameters to your `composer.json`:

```json
    "config": {
        "akeneo-appbundle-root-dir": "src",
        "akeneo-appbundle-vendor-name": "Acme",
        "akeneo-appbundle-bundle-name": "AppBundle"
    }
```

## Available packages & reference datas

| Package                         | Reference code       | Relation     | Type                                                         |
|---------------------------------|----------------------|--------------|-------------------------------------------------------------|
| `kiboko/akeneo-reference-datas` | `color.rgb.single`   | `ManyToOne`  | `Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB` |
| `kiboko/akeneo-reference-datas` | `color.rgb.multiple` | `ManyToMany` | `Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB` |

For now, the only package available is `kiboko/akeneo-reference-datas`

## Usage

### Initialize your `AppBundle`

Run the following command to build your bundle:

```bash
composer akeneo:init
```

### List the available reference datas

Run the following command to list the available reference datas.

```bash
composer akeneo:reference-data:list
```

### Add a `ManyToOne` relation with a `ColorRGB` in your `ProductValue`

Run the following command to install the color reference data as a many to one (single select):

```bash
composer akeneo:reference-data:add color.rgb.many-to-one
```

### Add a `ManyToMany` relation with a `ColorRGB` in your `ProductValue`

Run the following command to install the color reference data as a many to one (multiple select):

```bash
composer akeneo:reference-data:add color.rgb.many-to-many
```
