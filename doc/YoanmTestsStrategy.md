# Yoanm Tests Strategy compliance

  * [Configuration reference](#configuration-reference)
  * [Tests strategy rules validated by configuration reference](#rules-validated)
    * [Mandatory](#rules-validated-mandatory)
      * [**Base namespace**](#rules-validated-mandatory-base-namespace)
        * [PhpUnit](#rules-validated-mandatory-base-namespace-phpunit)
        * [Behat](#rules-validated-mandatory-base-namespace-behat)

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
## [Tests strategy rules](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules) validated by [configuration reference](#configuration-reference)

<a name="rules-validated-mandatory"></a>
### Mandatory

<a name="rules-validated-mandatory-base-namespace"></a>
#### [Base namespace](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-base-namespace) 

**With `autoload-dev -> psr-4`**

<a name="rules-validated-mandatory-base-namespace-phpunit"></a>
 * PhpUnit 
 
   *See [implementation](https://github.com/yoanm/initRepositoryWithPhpUnit)*
   
   ```json
   "autoload-dev": {
      "psr-4": {
        "Technical\\Unit\\VendorNamespace\\ProjectNamespace\\": "tests/Technical/Unit",
        "Technical\\Integration\\VendorNamespace\\ProjectNamespace\\": "tests/Technical/Integration",
        "Functional\\VendorNamespace\\ProjectNamespace\\": "tests/Functional"
      }
   }
   ```

<a name="rules-validated-mandatory-base-namespace-behat"></a>
 * Behat 
 
   *See [implementation](https://github.com/yoanm/initRepositoryWithBehat)*
   
   ```json
   "autoload-dev": {
      "psr-4": {
        "Functional\\VendorNamespace\\ProjectNamespace\\BehatContext\\": "features/bootstrap"
      }
   }
   ```
