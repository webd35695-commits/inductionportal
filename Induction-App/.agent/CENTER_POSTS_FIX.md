# Center Post Assignment Fixes

## Issues Identified

1.  **Incorrect Property in `AppliedPosts` Model**:
    The `AppliedPosts` model had `protected $database="applied_posts";`. This is incorrect; the property to specify the table name is `$table`. This could cause relationship failures if Laravel didn't correctly guess the table name.

2.  **Case Sensitivity in `cities` Model**:
    The `cities` model referenced `Center::class` for the `centers` relationship, but the class is defined as `class center` (lowercase) in `app/Models/center.php`. While PHP is case-insensitive for class names, it's best practice to match the definition, especially for autoloading compatibility.

## Fixes Applied

### 1. Fixed `AppliedPosts.php`
Changed `$database` to `$table`:
```php
// Before
protected $database="applied_posts";

// After
protected $table="applied_posts";
```

### 2. Fixed `cities.php`
Updated the `centers` relationship to use the correct class name:
```php
// Before
return $this->hasMany(Center::class, 'city_id');

// After
return $this->hasMany(center::class, 'city_id');
```

## Expected Result
These fixes ensure that:
- The `AppliedPosts` relationship on the `cities` model works correctly, allowing the `approved_applicants_count` to be calculated in the controller.
- The `centers` relationship on the `cities` model works reliably, allowing the `centers_count` to be calculated.
- The `admin/center-posts` page should now load the list of cities and their statistics without errors.
