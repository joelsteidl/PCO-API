# PCO API
API client for Planning Center. https://planningcenter.github.io/api-docs/

The PCO API (pco_api) module takes advantage of the Drupal httpClient https://api.drupal.org/api/drupal/core!lib!Drupal.php/function/Drupal%3A%3AhttpClient/8.2.x.

## Dependencies

Key Module - https://www.drupal.org/project/key

## Configuration

### Create a Personal Access Token in Planning Center.

This module currently uses the Personal Access Token method for authentication. You can create credentials by visiting https://accounts.planningcenteronline.com/ You should end up with a token and secret.

### Drupal configuration

1. Create a Key
** Visit /admin/config/system/keys/add
** Settings https://www.evernote.com/l/AMl-0tHsHyRELK0nP8Ms6O4fSbvA2NjI9vkB/image.png
** If you go the prefferred file route for the secret, please make sure there is no white space in the file!
2. Configure API Settings
** Add your API token, the API base url and reference the secret created in step #1
** Settings https://www.evernote.com/l/AMmcWL4KRX9Il7urBNECTnLZa4pP_p1TTnQB/image.png

## Usage

This module provides a pco_api.client service that can be used in Drupal hooks or in Classes using Dependency Injection.

The `connect` method accepts the following parameters:

```
   * @param string $method
   *   get, post, patch, delete, etc. See Guzzle documentation.
   * @param string $endpoint
   *   The PCO API endpoint (ex. people/v2/people)
   * @param array $query
   *   Query string parameters the endpoint allows (ex. ['per_page' => 50]
   * @param array $body (converted to JSON)
   *   Utilized for some endpoints
```
   
### Drupal hook_ example:

```
hook_cron() {
  // This would get 50 people from Planning Center on CRON.
  $client = Drupal::service('pco_api.client');
  $query = [
    'per_page' => 50,
    'include' => 'emails',
  ];
  $request = $client->connect('get', 'people/v2/people', $query, []);
  $results = json_decode($request);
}
```

### Controller using Dependency Injection example:

``` php
<?php

namespace Drupal\my_custom_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\pco_api\Client\PcoClient;

/**
 * Class MyController.
 *
 * @package Drupal\my_custom_module\Controller
 */
class MyController extends ControllerBase {

  /**
   * Drupal\pco_api\Client\PcoClient definition.
   *
   * @var \Drupal\pco_api\Client\PcoClient
   */
  protected $pcoApiClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(PcoClient $pco_api_client) {
    $this->pcoApiClient = $pco_api_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('pco_api.client')
    );
  }

  /**
   * Content.
   *
   * @return array
   *   Return array.
   */
  public function content() {
    // This would get 50 people from Planning Center on page load.
    $query = [
      'per_page' => 50,
      'include' => 'emails',
    ];
    $request = $this->pcoApiClient->connect('get', 'people/v2/people', $query, []);
    $results = json_decode($request);
    return [];
  }
}
```
