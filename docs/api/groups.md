## REST API: Group Endpoints

* These endpoints are used to create, read, update and delete add-on groups.
* An add-on group contains zero or more add-ons. An add-on contains zero or more options.
* Merchants can set up one or more global add-on groups and can also set up add-ons directly on individual products
* Global add-on groups can optionally be limited to certain product category IDs
* Products also inherit add-ons from their parent product, if any.
* Each global add-on group is a custom post and has a unique integer ID.
* Add-ons and options have unique GUIDs.

#### Create a New Global Add-on Group

`POST /wp-json/wc/v1/product-add-ons`

**Capability Required**

* `manage_woocommerce`

**Request Body**

- name: (string, optional) the global group name
- priority: (integer, optional) the priority of the group
- categories: (array of integers, optional) the product categories this group applies to or an empty array if it applies to all products

NOTE: You cannot add add-ons with this endpoint. To add use the appropriate [add-ons](add-ons.md) endpoint instead.

```
{
	name: 'Personalization Options',
	priority: 9,
	restrict_to_category_ids: [
		11,
		12,
		13,
		14
	],
}
```

**Success Response (200)**

On success, the complete newly created group object is returned. The returned add-ons array will always be empty.

```
{
	id: 10,
	name: 'Personalization Options',
	priority: 9,
	restrict_to_category_ids: [
		11,
		12,
		13
	],
	add-ons: []
}
```


#### Get a single Global Add-on Group

`GET /wp-json/wc/v1/product-add-ons/$group_ID`

**Capability Required**

* `manage_woocommerce`

**Request Body**

```
(none)
```

**Success Response (200)**

- `id`: the global group ID OR product ID
- `name`: (string)
    - the global group name
    - always empty for product add-ons
- `priority`: (integer)
    - for global groups, the priority of the group
    - always 10 for product add-ons
- `restrict_to_category_ids`: (array of integers)
    - for global groups, these are the product categories this group applies to or an empty array if it applies to all products
    - always an empty array for product add-ons
- `addons`: (array of GUIDs) the GUIDs of the add-ons in the group or product

```
{
	id: 10,
	name: 'Personalization Options',
	priority: 10,
	restrict_to_category_ids: [
		11,
		12,
		13
	],
	add-ons: [
		'aabc45a9-1629-4143-a6d1-5e38a627b5ce',
		'1fa1a5a0-bf75-479d-a82e-1da275e26f30',
		'e7b38ad0-f927-4ded-a3be-b859caeeef0c'
	]
}
```

#### Get all the Global Add-on Groups

`GET /wp-json/wc/v1/product-add-ons`

**Capability Required**

* `manage_woocommerce`

**Request Body**

```
(none)
```

**Success Response (200)**

```
{
	[
		{
			id: 10,
			name: 'Personalization Options',
			priority: 10,
			restrict_to_category_ids: [
				11,
				12,
				13
			],
			add-ons: [
				'aabc45a9-1629-4143-a6d1-5e38a627b5ce',
				'1fa1a5a0-bf75-479d-a82e-1da275e26f30',
				'e7b38ad0-f927-4ded-a3be-b859caeeef0c'
			]
		},
		{
			id: 14,
			name: 'Moar Options',
			priority: 15,
			restrict_to_category_ids: [
				11,
				12,
				13
			],
			add-ons: [
				'6c9d0cc4-a236-4169-b12f-f843a080aff6',
				'737ed40a-77b7-47ff-a389-2abd27a9be9b',
				'c995e4eb-15cb-4de0-9874-28f645b9bb11'
			]
		}

	]
}
```


#### Update a Global Add-on Group

`PUT /wp-json/wc/v1/product-add-ons/$group_ID`

**Capability Required**

* `manage_woocommerce`

**Request Body**

- name: (string, optional) the global group name; always empty for product add-ons
- priority: (integer, optional) for global groups, the priority of the group; always 10 for product add-ons
- restrict_to_category_ids: (array of integers, optional) for global groups, the product categories this group applies to or an empty array if it applies to all products; also an empty array for products 

NOTE: You cannot add, modify or remove add-ons with this endpoint. To add, modify or remove add-ons, use the [add-ons](add-ons.md) endpoints instead.

```
{
	name: 'Personalization Options',
	priority: 9,
	restrict_to_category_ids: [
		11,
		12,
		13,
		14
	],
}
```

**Success Response (200)**

On success, the entire group object is returned including any changes.

```
{
	id: 10,
	name: 'Personalization Options',
	priority: 9,
	restrict_to_category_ids: [
		11,
		12,
		13
	],
	add-ons: [
		'aabc45a9-1629-4143-a6d1-5e38a627b5ce',
		'1fa1a5a0-bf75-479d-a82e-1da275e26f30',
		'e7b38ad0-f927-4ded-a3be-b859caeeef0c'
	]
}
```


#### Delete a Global Add-on Group

`DELETE /wp-json/wc/v1/product-add-ons/$group_ID`

**Capability Required**

* `manage_woocommerce`

**Request Body**

NOTE: Only works for global add-on groups. You cannot delete product add-ons using this endpoint. To remove add-ons, use the [add-ons](add-ons.md) endpoints instead.

```
(none)
```

**Success Response (200)**

```
(empty)
```

