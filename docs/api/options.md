## REST API: Options

* The fields available for an option vary based on the add-on type.

#### Additional Custom Price Input (custom-price-input)

- `label` (string, optional)
- `min` (price, optional)
    - set to minimum price customer can input (e.g. 1.00), or
    - set to `false` to have no minimum
- `max` (price, optional)
    - set to maximum price customer can input (e.g. 10.00), or
    - set to `false` to have no maximum

**Sample Option**
```
{
	label: 'Add $1 for each additional blank card you'd like up to ten cards',
	min: 0,
	max: 10
}
```

#### Additional Price Multiplier (additional-price-multiplier)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when the option is selected
- `min` (integer, optional)
    - set to minimum multiplier the customer can input (e.g. 1), or
    - set to `false` to have no minimum
- `max` (integer, optional)
    - set to maximum multiplier the customer can input (e.g. 10), or
    - set to `false` to have no maximum

**Sample Option**
```
{
	label: 'Add up to ten additional cards at $1 each',
	price: 1.00,
	min: 0,
	max: 10
}
```

#### Checkboxes (checkboxes)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when the option is selected

**Sample Option**
```
{
	label: 'Comic Sans Font',
	price: 1.00,
}
```

#### Custom Input - Textarea (textarea)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when the option has text entered
- `min` (integer, optional)
    - set to minimum number of characters required
    - set to `false` to have no minimum
- `max` (integer, optional)
    - set to maximum number of characters allowed
    - set to `false` to have no maximum

**Sample Option**
```
{
	label: 'Enter a custom message to be printed on your shirt, up to 40 characters',
	price: 10.00,
	min: 0,
	max: 40
}
```

#### Custom Input - Any Text (text)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when the option has text entered
- `min` (integer, optional)
    - set to minimum number of characters required
    - set to `false` to have no minimum
- `max` (integer, optional)
    - set to maximum number of characters allowed
    - set to `false` to have no maximum

**Sample Option**
```
{
	label: 'Enter a custom message to be printed on your shirt, up to 40 characters',
	price: 10.00,
	min: 0,
	max: 40
}
```

#### Custom Input - Email Address (email-address)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when the option has an email address entered

**Sample Option**
```
{
	label: 'Enter an email address to get notifications automatically for just $10',
	price: 10.00,
}
```

#### Custom Input - Only Letters (only-letters)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when the option has text entered
- `min` (integer, optional)
    - set to minimum number of characters required
    - set to `false` to have no minimum
- `max` (integer, optional)
    - set to maximum number of characters allowed
    - set to `false` to have no maximum

**Sample Option**
```
{
	label: 'Enter a custom message to be printed on your shirt, up to 40 characters',
	price: 10.00,
	min: 0,
	max: 40
}
```

#### Custom Input - Only Numbers and Letters (alphanumeric)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when the option has text entered
- `min` (integer, optional)
    - set to minimum number of characters required
    - set to `false` to have no minimum
- `max` (integer, optional)
    - set to maximum number of characters allowed
    - set to `false` to have no maximum

**Sample Option**
```
{
	label: 'Enter a custom message to be printed on your shirt, up to 40 characters',
	price: 10.00,
	min: 0,
	max: 40
}
```

#### Custom Input - Only Numbers (only-numbers)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when the option has text entered
- `min` (integer, optional)
    - set to minimum number of characters required
    - set to `false` to have no minimum
- `max` (integer, optional)
    - set to maximum number of characters allowed
    - set to `false` to have no maximum

**Sample Option**
```
{
	label: 'Enter a zip code for special monitoring for $10 a month',
	price: 10.00,
	min: 6,
	max: 6
}
```

#### File Upload (file-upload)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when a file is attached

**Sample Option**
```
{
	label: 'Add a custom photo to your coffee mug for just $5',
	price: 5.00
}
```

#### Radio Buttons (radiobuttons)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when that radio button is selected

NOTE: Usually you will have two or more radiobutton options in an addon

**Sample Option**
```
{
	label: 'Comic Sans',
	price: 5.00
}
```

#### Select Box (selectbox)

- `label` (string, optional)
- `price` (price, optional)
    - the price to charge when that option is selected

NOTE: Usually you will have two or more selectbox options in an addon

**Sample Option**
```
{
	label: 'Comic Sans',
	price: 5.00
}
```
