The shop module allows the checkout to exist as either a single page, or a multi-page solution, and somewhere in-between.

The multi-page solution is known as `Stepped Checkout`. The single page solution utilises `CheckoutForm`. In both cases, you will need to understand what is `CheckoutComponent`.

## Checkout Components

Checkout components are a solution to encapsulate pieces of the checkout, including:

 * Form fields
 * Required form fields
 * Data validation
 * Data Retrieval (getting)
 * Data Storing (setting)

This encapsulation allows for more flexible checkout customisations than a single form.

The checkout component system was inspired by `GridField`'s components.

## Single Step Checkout

A single step checkout is somewhat limited, because it requires the use of ajax to modify the form if one part relies on another.

You can modify the components in your checkout by subclassing `CheckoutComponentConfig`. Example where no shipping and billing Addresses are added (eg. for virtual goods):

```php
class CustomSinglePageCheckoutComponentConfig extends CheckoutComponentConfig
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
        $this->addComponent(CustomerDetailsCheckoutComponent::create());
        if (Checkout::member_creation_enabled() && !Member::currentUserID()) {
            $this->addComponent(MembershipCheckoutComponent::create());
        }
        if (count(GatewayInfo::getSupportedGateways()) > 1) {
            $this->addComponent(PaymentCheckoutComponent::create());
        }
        $this->addComponent(NotesCheckoutComponent::create());
        $this->addComponent(TermsCheckoutComponent::create());
    }
}
```

You then use the Injector to use your class instead of CheckoutComponentConfig:

```yaml
# Put this in your config.yml file
Injector:
  CheckoutComponentConfig:
    class: CustomSinglePageCheckoutComponentConfig
```

## Single Step Checkout with saving addresses to AddressBook
To have Silvershop save the clients addresses (shipping and billing) to the AddressBook, selectable from the account page and visible to admins in the CMS, you have two options:
- 1: either via an extension to Order (there are extension hooks for this, onSetShippingAddress and onSetBillingAddress ) 
- 2: you can create your own CheckoutComponentConfig class and call setAddToAddressBook on the ShipingAddress and BillingAddress components from there.

Example:
```php
class CustomSinglePageCheckoutComponentConfig extends CheckoutComponentConfig
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
        $shipping_address = ShippingAddress::create();
        $shipping_address->setAddToAddressBook(true);

        $billing_address = BillingAddress::create();
        $billing_address->setAddToAddressBook(true);

        $this->addComponent(CustomerDetails::create());
        $this->addComponent($shipping_address);
        $this->addComponent($billing_address);
        if (count(GatewayInfo::getSupportedGateways()) > 1) {
            $this->addComponent(Payment::create());
        }
        $this->addComponent(Notes::create());
        $this->addComponent(Terms::create());
    }
}


```


You then use the Injector to use your class instead of CheckoutComponentConfig:

```yaml
# Put this in your config.yml file
Injector:
  CheckoutComponentConfig:
    class: CustomSinglePageCheckoutComponentConfig
```

## Multi Step Checkout

See [Multi Step Checkout](Multi_Step_Checkout.md).
