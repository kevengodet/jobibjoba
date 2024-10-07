# keven/jobijoba - PHP client for JobiJoba API

## Installation

```shell
$ composer install keven/jobijoba
```

You can view the client working locally by running the following command line
with your own API credentials:

```shell
$ JOBIJOBA_CLIENT_ID=xxx JOBIJOBA_CLIENT_SECRET=xxx php -S localhost:8080 ./demo
```

Then go to http://localhost:8080 in your browser.

## Usage

```php
<?php

use Keven\JobiJoba\ApiClient;

$jobijoba = new ApiClient($clientId, $clientSecret);
$jobs = $jobijoba->search("chauffeur", "Amiens");
foreach ($jobs as $job) {
    echo $job->title;
    
    // Available properties:
    //    "id": "",
    //    "link": "",
    //    "title": "",
    //    "description": "",
    //    "publicationDate": "",
    //    "coordinates": "",
    //    "city": "",
    //    "postalCode": "",
    //    "department": "",
    //    "region": "",
    //    "sector": "",
    //    "jobtitle": "",
    //    "company": "",
    //    "contractType": [],
    //    "salary": ""
 }
```

That's it.
