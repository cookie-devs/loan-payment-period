[![Build Status](https://scrutinizer-ci.com/g/kaurikk/loan-payment-period/badges/build.png?b=master)](https://scrutinizer-ci.com/g/kaurikk/loan-payment-period/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kaurikk/loan-payment-period/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kaurikk/loan-payment-period/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/kaurikk/loan-payment-period/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kaurikk/loan-payment-period/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0ed302ff-9e93-4445-8fc2-bcec227afa57/mini.png)](https://insight.sensiolabs.com/projects/0ed302ff-9e93-4445-8fc2-bcec227afa57)
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
