File format
===========

This file declares what is expected in a reference data YAML declaration.

The processor will be expected to create interfaces and classes representing
these data structures.

Enums
-----

An enum is a set of constant declarations, no code logic will be present.

### Specification:

```yaml
enums:
  - name: <class-name>
    description: <doc>
    consts:
      <code>: <string> | <integer>
      ...
```

### Sample code:

```yaml
enums:
  - name: Kiboko\Component\Barcode\Model\PackagingTypesInterface
    consts:
      UNIT: unit
      PACK: pack
      SET: set
      PALLET: pallet
```

Contracts
---------

A contract is a model interface, use it to declare how entities should
appear from the outside, and their expected behaviors.

### Specification:

```yaml
contracts:
  - name: <class-name>
    description: <doc>
    consts:
        <code>: <string> | <integer>
        ...

    fields:
      <code>:
        type: string
        length: <integer; defaults: 255>
        description: <doc>

      <code>:
        type: decimal
        precision: <integer; defaults: 12>
        scale: <integer; defaults: 4>
        description: <doc>

      <code>:
        type: dimension
        precision: <integer; defaults: 12>
        scale: <integer; defaults: 4>
        family: <class-name>
        description: <doc>

      <code>:
        type: money
        precision: <integer; defaults: 12>
        scale: <integer; defaults: 4>
        currency: <currency-code>
        description: <doc>

      <code>:
        type: enum
        enumType: <class-name>
        description: <doc>

      <code>:
        type: wysiwyg
        description: <doc>

      <code>:
        type: <class-name>
        description: <doc>
      ...
```

### Sample code:

```yaml
contracts:
  - name: Kiboko\Component\Measure\Model\MeasureInterface
    fields:
      name:
        type: string
        length: 50

      valueFactor:
        type: decimal
        precision: 24
        scale: 12

      weight:
        type: dimension
        precision: 12
        scale: 4
        family: Akeneo\Bundle\MeasureBundle\Family\WeightFamilyInterface

      cost:
        type: money
        precision: 12
        scale: 4
        currency: EUR

      type:
        type: enum
        enumType: Kiboko\Component\Barcode\Model\PackagingTypesInterface

      description:
        type: wysiwyg

      barcode:
        type: Kiboko\Bundle\BarcodeBundle\Entity\Barcode
```

Entities
--------

Entities inherits from contracts and declares the behavior code.

Every declaration present in a contract won't be editable in an entity,
thus it won't be required to redeclare fields already present in a
contract in the entity.

### Specification:

```yaml
entities:
  - name: <class-name>
    description: <doc>
    contracts:
      - <class-name>
      ...

    consts:
      <string>: <string> | <integer>
      ...

    fields:
      <code>:
        type: string
        length: <integer; defaults: 255>
        description: <doc>

      <code>:
        type: decimal
        precision: <integer; defaults: 12>
        scale: <integer; defaults: 4>
        description: <doc>

      <code>:
        type: dimension
        precision: <integer; defaults: 12>
        scale: <integer; defaults: 4>
        family: <class-name>
        description: <doc>

      <code>:
        type: enum
        enumType: <class-name>
        description: <doc>

      <code>:
        type: wysiwyg
        description: <doc>

      <code>:
        type: <class-name>
        description: <doc>
      ...
```

### Sample code:

```yaml
contracts:
  - name: Kiboko\Bundle\MeasureBundle\Entity\Measure
    contracts:
      - Kiboko\Component\Measure\Model\MeasureInterface
    fields:
      name:
        type: string
        length: 50

      multiplier:
        type: decimal
        precision: 24
        scale: 12

      weight:
        type: dimension
        precision: 12
        scale: 4
        family: Akeneo\Bundle\MeasureBundle\Family\WeightFamilyInterface

      cost:
        type: money
        precision: 12
        scale: 4
        currency: EUR

      type:
        type: enum
        enumType: Kiboko\Component\Barcode\Model\PackagingTypesInterface

      description:
        type: wysiwyg

      barcode:
        type: Kiboko\Bundle\BarcodeBundle\Entity\Barcode
```
