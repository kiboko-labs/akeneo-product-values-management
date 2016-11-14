Manage your Akeneo reference data
=====

This package is a composer plugin used to help you manage your Akeneo reference datas.

*__WARNING:__ This package is experimental, do not use it on production*

## Installation

Just require the package in your installation

```bash
composer require "kiboko/akeneo-product-values-management=dev-master"
```

## Usage

### Initialize your `AppBundle`

Just run the following command and follow the instructions:

```bash
composer akeneo:init
```

### Initialize your `ProductValue`

Just run the following command and follow the instructions:

```bash
composer akeneo:reference-data:build
```
