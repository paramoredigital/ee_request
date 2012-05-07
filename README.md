ee_request
===============
ExpressionEngine plugin for grabbing SESSION, REQUEST, GET, and POST data.

Tags
===============
session	= Retrieve data form SESSION.
request	= Retrieve data form REQUEST.
get 	= Retrieve data form GET.
post 	= Retrieve data form POST.

Parameters
===============
name = A single key or dot notated path to retrieve data from nested arrays. For dot notated paths, use keys or indexes wrapped in braces, i.e. key.key, [0][1], key.[3].key

Examples
===============
{exp:ee_request:session name='customer.cart.error'} would return $_SESSION['customer']['cart']['error'].
{exp:ee_request:get name='first_name'} would return $_GET['first_name'].