Request Plugin for ExpressionEngine
---

ExpressionEngine plugin for grabbing SESSION, REQUEST, GET, and POST data.

## Tags

`{exp:ee_request:session}`
> Retrieve data from SESSION.

`{exp:ee_request:request}`
> Retrieve data from REQUEST.

`{exp:ee_request:get}`
> Retrieve data from GET.

`{exp:ee_request:post}`
> Retrieve data from POST.

`{exp:ee_request:cookie}`
> Retrieve COOKIE data.

## Parameters

name
> A single key or dot notated path to retrieve data from nested arrays. For dot notated paths, use keys or indexes wrapped in braces, i.e. key.key, [0][1], key.[3].key

## Examples

`{exp:ee_request:session name='customer.cart.error'}`
> Returns `$_SESSION['customer']['cart']['error']`

`{exp:ee_request:get name='first_name'}`
> Returns `$_GET['first_name']`
