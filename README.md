# loan-payment-period

Library to generate loan payment periods based on payment schedule. Contains 3 main parts:

 * PaymentPeriodsFactory - generates payment periods based on payment schedule
 * PaymentPeriods - holds payment periods collection
 * Period - single period

## Basic usage
See [more](https://github.com/kaurikk/loan-payment-schedule/blob/master/README.md#basic-usage) about PaymentSchedule.

```php
$paymentPeriods = PaymentPeriodsFactory::generate($paymentSchedule);

// How many periods are in collection
$numberOfPayments = $paymentPeriods->getNoOfPeriods();

// Get array of Periods from collection
$periods = $paymentPeriods->getPeriods();


// Get first period from Periods array
$firstPeriod = current($periods);
// How long is period (days)
$firstPeriod->getLength();
// Period start date
$firstPeriod->getStart();
// Period end date
$firstPeriod->getEnd();
```
