# PHP_Laravel12_Get_Current_Date_And_Time_using_API

# Step 1 : Install Laravel 12 and Create Project
```php
composer create-project laravel/laravel PHP_Laravel12_Get_Current_Date_And_Time_using_API
``` 
# Step 2 : Create API Controller  for Current date and Time 
```php
php artisan make:controller Api/DateTimeController
```
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DateTimeController extends Controller
{
    public function getCurrentDateTime()
    {
        //  India Time (IST)
        $now = Carbon::now('Asia/Kolkata');

        return response()->json([
            'status'     => true,
            'message'    => 'Current Date and Time',
            'country'    => 'India',              //  COUNTRY ADDED
            'date'       => $now->toDateString(),
            'time'       => $now->toTimeString(),
            'date_time'  => $now->toDateTimeString(),
            'timezone'   => 'Asia/Kolkata',
        ]);
    }
}
```


# Step 3 : Create Route for Api.php
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DateTimeController;

Route::get('/current-date-time', [DateTimeController::class, 'getCurrentDateTime']);
``` 
# Step 4 : Now Run server in Terminal 
```php
php  artisan serve
``` 
# Now Open the Postman
# Select GET Method
# Paste Url http://127.0.0.1:8000/api/current-date-time
# Now Click send Button and show the result

<img width="1731" height="573" alt="image" src="https://github.com/user-attachments/assets/4c9d68a8-4de8-456d-a6a9-b75ab6abb9e9" />

 
