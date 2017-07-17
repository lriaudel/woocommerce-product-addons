## REST API: Add-On Endpoints

* These endpoints are used to create, read, update and delete add-ons.
* An add-on contains zero or more options.
* Add-ons are organized into global add-on groups or individual products. For more information about global add-on groups, see the [groups](groups.md) endpoint documentation.

#### Create a New Add-on in a Group or Product

`POST /wp-json/wc/v1/product-add-ons/$product_or_group_ID/add-ons`

**Capability Required**

* `manage_woocommerce`

**Request Body**

- type: (string, required) one of the following
    - `custom-price-input` : Additional custom price input
    - `additional-price-multipler` : Additional price multiplier
    - `checkboxes` : Checkboxes 
    - `textarea` : Custom input (textarea)
    - `text` : Custom input (text) - Any Text
    - `email-address` : Custom input (text) - Email Address
    - `only-letters` : Custom input (text) - Only Letters
    - `alphanumeric` Custom input (text) - Only Letters and Numbers
    - `only-numbers` : Custom input (text) - Only Numbers
    - `file-upload` : File upload
    - `radiobuttons` : Radio buttons
    - `selectbox` : Select box
- name: (string, required) the name to display on the front-end for this add-on
- description: (string, optional) the description, if any, to display on the front-end; defaults to empty string
- required: (boolean, optional) whether or not the customer must choose/complete at least one option from the add-on; defaults to false

NOTE: You cannot add options using this endpoint. Use the update endpoint below instead.

```
{
	type: 'checkboxes',
	name: 'Special Engraving Font',
	description: 'Upgrade from the standard font (Arial) to a special one.',
	required: false,
	options: []
}
```

**Success Response (200)**

On success, the complete newly created add-on object is returned. The returned options array will always be empty.

```
{
	guid: 'aabc45a9-1629-4143-a6d1-5e38a627b5ce',
	type: 'checkboxes',
	name: 'Special Engraving Font',
	description: 'Upgrade from the standard font (Arial) to a special one.',
	required: false,
	options: []
}
```


#### Get all the Add-ons in a Group or Product

`GET /wp-json/wc/v1/product-add-ons/$product_or_group_ID/add-ons`

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
			guid: 'aabc45a9-1629-4143-a6d1-5e38a627b5ce',
			type: 'checkboxes',
			name: 'Special Engraving Font',
			description: 'Upgrade from the standard font (Arial) to a special one.',
			required: false,
			options: []
		},
		{
			guid: '1fa1a5a0-bf75-479d-a82e-1da275e26f30',
			type: 'text',
			name: 'Special Engraved Message',
			description: 'Enter up to two lines of 40 characters each.',
			required: false,
			options: []
		}
	]
}
```

#### Update an Add-on in a Group or Project

`PUT /wp-json/wc/v1/product-add-ons/$product_or_group_ID/add-ons/$add_on_GUID`

**Capability Required**

* `manage_woocommerce`

**Request Body**

- type: (string, optional)
    - see above for accepted types
    - you cannot change the type while the addon has options
- name: (string, optional)
    - if specified, cannot be empty
- description: (string, optional)
- required: (boolean, optional)
- options: (array of option objects, optional)

NOTE: See [options](options.md) for fields supported for each type of add-on

```
{
	type: 'text',
	name: 'Special Engraved Message',
	description: 'Enter up to two lines of 40 characters each.',
	required: false,
	options: [
		{
			label: 'Line One',
			price: 5.00,
			min_characters: 1,
			max_characters: 40
		},
		{
			label: 'Line Two',
			price: 5.00,
			min_characters: 1,
			max_characters: 40
		}
	]
}
```

**Success Response (200)**

On success, the entire add-on object is returned including any changes.

```
{
	guid: '1fa1a5a0-bf75-479d-a82e-1da275e26f30',
	type: 'text',
	name: 'Special Engraved Message',
	description: 'Enter up to two lines of 40 characters each.',
	required: false,
	options: [
		{
			label: 'Line One',
			price: 5.00,
			min_characters: 1,
			max_characters: 40
		},
		{
			label: 'Line Two',
			price: 5.00,
			min_characters: 1,
			max_characters: 40
		}
	]
}
```

#### Delete an Add-on from a Group or Product

`DELETE /wp-json/wc/v1/product-add-ons/$product_or_group_ID/add-ons/$add_on_GUID`

**Capability Required**

* `manage_woocommerce`

**Request Body**

```
(none)
```

**Success Response (200)**

```
(empty)
```

