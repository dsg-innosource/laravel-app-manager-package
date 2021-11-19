# Laravel Application Manager

## Custom Data
You can add any data you would like to the reports that get sent. Just add your data to your `AppServiceProvider` like this:

```php
    public function boot()
    {
        LaravelApplicationManager::setCustomData(function () {
            return [
                'my_custom_property' => MyCustomClass::myFunction(),
            ];
        });
    }
```
