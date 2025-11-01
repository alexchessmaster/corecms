# Permissions and Roles Documentation

## Overview
This application uses **Spatie's Laravel Permission** package for authorization. The system is **permission-based** rather than role-based, providing granular control over user access.

## Roles

### Super Admin
- Has ALL permissions
- Cannot be restricted
- User: alex@alex.com

### Admin
- Has ALL standard permissions
- Can manage users, system settings, and all content
- User: admin@example.com (password: password)

### Editor
- Can manage content but NOT users or critical system settings
- Can view, create, edit, delete, and restore most content types
- User: editor@example.com (password: password)

### Author
- Can only manage their OWN content
- Limited to specific content types
- Can view shared resources (fields, widgets, menus, languages)
- User: author@example.com (password: password)

## Permissions Structure

Each resource has 9 permission types:

1. **view [resource]** - View all instances of the resource
2. **view own [resource]** - View only user's own instances
3. **create [resource]** - Create new instances
4. **edit [resource]** - Edit all instances
5. **edit own [resource]** - Edit only user's own instances
6. **delete [resource]** - Delete instances
7. **delete own [resource]** - Delete only user's own instances
8. **restore [resource]** - Restore soft-deleted instances
9. **force delete [resource]** - Permanently delete instances

## Resources

The following resources have full permission sets:

- users
- categories
- articles
- pages
- tags
- product tags
- product categories
- product authors
- products
- books
- book authors
- book genres
- uploads
- fields
- widgets
- widget field values
- widgetables
- field widgets
- menus
- settings
- languages
- translation texts
- redirects
- redirect slug changes
- url logs
- booking time slots
- booking reservations
- ai chats
- ai messages
- ai personas
- commentables

## Total Permissions Created

**288 permissions** (32 resources × 9 actions each)

## Policy Logic Examples

### Admin/Editor Access
```php
// Can view ALL items
public function viewAny(User $user): bool
{
    return $user->can('view categories');
}
```

### Author Access (Own Content Only)
```php
// Can view all OR their own
public function view(User $user, Category $category): bool
{
    return $user->can('view categories') 
        || ($category->user_id === $user->id && $user->can('view own categories'));
}
```

## How to Use

### Check Permission in Controller
```php
$user->can('edit articles');
$user->can('delete own products');
```

### Check Permission in Blade
```blade
@can('create pages')
    <a href="{{ route('pages.create') }}">Create Page</a>
@endcan

@can('edit', $article)
    <a href="{{ route('articles.edit', $article) }}">Edit</a>
@endcan
```

### Assign Permission to Role
```php
$role = Role::findByName('editor');
$role->givePermissionTo('view articles');
```

### Assign Permission to User
```php
$user->givePermissionTo('edit articles');
```

### Check in Policy
The policies automatically use these permissions via `$user->can('permission name')`.

## Seeder Information

- **File**: `database/seeders/RoleAndPermissionSeeder.php`
- **Run with**: `php artisan migrate:fresh --seed`
- Creates all roles, permissions, and assigns them appropriately

## Advantages of Permission-Based System

1. **Flexibility**: Assign specific permissions to users or roles
2. **Granularity**: Fine-grained control over what users can do
3. **Maintainability**: Change permissions without modifying code
4. **Scalability**: Easily add new resources and permissions
5. **Custom Roles**: Create custom roles with any combination of permissions

## Future Additions

To add a new resource (e.g., "newsletters"):

1. Add to `$resources` array in `RoleAndPermissionSeeder.php`
2. Re-run seeder or manually create permissions
3. Assign permissions to appropriate roles
4. Create policy using permission checks
