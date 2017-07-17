## REST API: Options

* These endpoints are used to retrieve the selected add-ons (if any) for items in the order

#### Get the Add-ons for Items in an Order

`GET /wp-json/wc/v1/product-add-ons/order/$order_ID`

**Capability Required**

* `manage_woocommerce`

**Request Body**

```
(none)
```

**Success Response (200)**

For each item in the cart, the selected add-on options and their values are returned.

```
{
	[
		{
			item_id: 64,
			options: [
				{
					guid: 'aabc45a9-1629-4143-a6d1-5e38a627b5ce',
					name: 'Special Engraving Font',
					label: 'Font',
					price: 5.00,
					value: 'Comic Sans',
				},
				{
					guid: '1fa1a5a0-bf75-479d-a82e-1da275e26f30',
					name: 'Special Engraved Message',
					label: 'Message',
					price: 5.00,
					value: 'Together forever',
				}
			]
		},
		{
		}
	]
}
```

