<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {

    return [
        'nama' => $faker->name,
        'no_telp' => (Int) str_replace('+', '', $faker->unique()->e164PhoneNumber),
        'alamat' => $faker->streetAddress,
    ];
});

$factory->define(App\Department::class, function (Faker\Generator $faker) {

    return [
        'nama' => $faker->unique()->jobTitle,
    ];
});

$factory->define(App\Staff::class, function (Faker\Generator $faker) {

    return [
        'nama' => $faker->name,
        'no_telp' => (Int) str_replace('+', '', $faker->unique()->e164PhoneNumber),
        'alamat' => $faker->streetAddress,
        'department_id' => random_int(App\Department::min('id'), App\Department::max('id')),
    ];
});

$factory->define(App\Service::class, function (Faker\Generator $faker) {
    $customer_id = random_int(App\Customer::min('id'), App\Customer::max('id'));
    $ref_no =  time() . rand(10*45, 100*98) . $customer_id;
    $array_customer_car_ids = App\Car::where('customer_id', $customer_id)->get(['id'])->toArray();
    if (count($array_customer_car_ids) > 0) {
        $array_customer_car_ids_random_index = array_rand($array_customer_car_ids, 1);
        $car_id = $array_customer_car_ids[$array_customer_car_ids_random_index]['id'];
    } else {
        $car_id = 0;
    }

    return [
        'status_transaksi_id' => random_int(App\TransactionStatus::min('id'), App\TransactionStatus::max('id')),
        'status_service_id' => random_int(App\ServiceStatus::min('id'), App\ServiceStatus::max('id')),
        'payment_id' => random_int(App\Payment::min('id'), App\Payment::max('id')),
        // 'service_types_id' => random_int(App\ServiceType::min('id'), App\ServiceType::max('id')),
        'ref_no' => $ref_no,
        'customer_id' => $customer_id,
        'car_id' => $car_id
    ];
});

$factory->define(App\ServiceStaff::class, function (Faker\Generator $faker) {

    return [
        'staff_id' => random_int(App\Staff::min('id'), App\Staff::max('id')),
        'service_id' => random_int(App\Service::min('id'), App\Service::max('id')),
    ];
});

$factory->define(App\ServiceServiceType::class, function (Faker\Generator $faker) {

    return [
        'service_id' => random_int(App\Service::min('id'), App\Service::max('id')),
        'service_type_id' => random_int(App\ServiceType::min('id'), App\ServiceType::max('id')),
    ];
});

$factory->define(App\Inventory::class, function (Faker\Generator $faker) {

    return [
        'nama' => $faker->unique()->name,
        'harga' => $faker->randomNumber($nbDigits = 4),
        'qty' => random_int(1, 10),
        'category_id' => random_int(App\Category::min('id'), App\Category::max('id')),
    ];
});

$factory->define(App\InventoryService::class, function (Faker\Generator $faker) {
    $inventory_id = random_int(App\Inventory::min('id'), App\Inventory::max('id'));
    $inventory_qty = App\Inventory::whereId($inventory_id)->first()->qty; // get QTY COLUMN!!!
    $inventory_price = App\Inventory::whereId($inventory_id)->first()->harga;
    // $inventory_category = App\Inventory::whereId($inventory_id)->first()->category_id;

    $service_id = random_int(App\Service::min('id'), App\Service::max('id'));

    // "qty" get generated "0" then REMOVE the "inventory_id"
    $rand_qty = random_int(0, $inventory_qty);
    if($rand_qty == 0) {
        $inventory_id = 0;
    }

    return [
        'inventory_id' => $inventory_id,
        'qty' => $rand_qty,
        'service_id' => $service_id,
        'total_harga' => ($inventory_price * $rand_qty),
    ];
});

$factory->define(App\Car::class, function (Faker\Generator $faker) {

    return [
        'nama' => 'inova',
        'jenis' => 'automatic',
        'no_plat' => 'AKB 100',
        'model' => 'mini bus',
        'customer_id' => random_int(App\Customer::min('id'), App\Customer::max('id')),
    ];
});
