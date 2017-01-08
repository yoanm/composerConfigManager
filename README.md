# intRepositoryWithComposer

> *Implementation is compliant to this* **[Tests strategy](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md)**

Command to initialize composer for a repository

 * [Configuration reference](#configuration-reference)
 * [Tests strategy rules validated by configuration reference](#rules-validated)
  * [Mandatory](#rules-validated-mandatory)
    * [Base namespace](#rules-validated-mandatory-base-namespace)

## Configuration reference
```json
{
  "autoload-dev": {
    "psr-4": {
      "Technical\\Unit\\VendorNamespace\\ProjectNamespace\\": "tests/Technical/Unit",
      "Technical\\Integration\\VendorNamespace\\ProjectNamespace\\": "tests/Technical/Integration",
      "Functional\\VendorNamespace\\ProjectNamespace\\": "tests/Functional",
      "Functional\\VendorNamespace\\ProjectNamespace\\BehatContext\\": "features/bootstrap"
    }
  }
}
```

<a name="rules-validated"></a>
## [Tests strategy rules](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules) validated by [configuration reference](#configuration-reference)

<a name="rules-validated-mandatory"></a>
### Mandatory

<a name="rules-validated-mandatory-base-namespace"></a>
#### [Base namespace](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-base-namespace) 

**With `autoload-dev -> psr-4`**

```json
  "autoload-dev": {
    "psr-4": {
      "Technical\\Unit\\VendorNamespace\\ProjectNamespace\\": "tests/Technical/Unit",
      "Technical\\Integration\\VendorNamespace\\ProjectNamespace\\": "tests/Technical/Integration",
      "Functional\\VendorNamespace\\ProjectNamespace\\": "tests/Functional",
      "Functional\\VendorNamespace\\ProjectNamespace\\BehatContext\\": "features/bootstrap"
    }
  }
```
