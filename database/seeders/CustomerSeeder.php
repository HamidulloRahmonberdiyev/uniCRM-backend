<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    private $firstNamesMale = [
        'Abbos',
        'Abdulla',
        'Akbar',
        'Alisher',
        'Anvar',
        'Asad',
        'Aziz',
        'Bahrom',
        'Dilshod',
        'Elyor',
        'Farhod',
        'Furqat',
        'Jasur',
        'Kamol',
        'Murod',
        'Nasir',
        'Nodirjon',
        'Olim',
        'Qosim',
        'Ravshan',
        'Rustam',
        'Sardor',
        'Sherzod',
        'Toshpulat',
        'Ulugbek',
        'Xurshid',
        'Yorqin'
    ];

    private $firstNamesFemale = [
        'Adolat',
        'Aziza',
        'Dilnoza',
        'Gulbahor',
        'Gulchеhra',
        'Gulnora',
        'Hikoyat',
        'Kamola',
        'Malika',
        'Mohira',
        'Munira',
        'Nadira',
        'Nigora',
        'Nodira',
        'Ойгул',
        'Сабрина',
        'Shakhnoza',
        'Shohista',
        'Zebiniso',
        'Zulayho'
    ];

    private $lastNamesMale = [
        'Abdurahmonov',
        'Akbarov',
        'Aliyev',
        'Azimov',
        'Boboyev',
        'Ergashev',
        'Hakimov',
        'Ismoilov',
        'Karimov',
        'Mahmudov',
        'Mirzayev',
        'Normatov',
        'Ochilov',
        'Qosimov',
        'Rahimov',
        'Sabirov',
        'Sodiqov',
        'Tursunov',
        'Umarov',
        'Yakubov',
        'Yuldashev',
        'Zoirov'
    ];


    private $lastNamesFemale = [
        'Abdurahmonova',
        'Akbarova',
        'Aliyeva',
        'Azimova',
        'Boboyeva',
        'Ergasheva',
        'Hakimova',
        'Ismoilova',
        'Karimova',
        'Mahmudova',
        'Mirzayeva',
        'Normatova',
        'Ochilova',
        'Qosimova',
        'Rahimova',
        'Sabirova',
        'Sodiqova',
        'Tursunova',
        'Umarova',
        'Yakubova',
        'Yuldasheva',
        'Zoirova'
    ];

    private $middleNamesMale = [
        'Abbos o\'g\'li',
        'Abdulla o\'g\'li',
        'Akbar o\'g\'li',
        'Alisher o\'g\'li',
        'Anvar o\'g\'li',
        'Asad o\'g\'li',
        'Aziz o\'g\'li',
        'Bahrom o\'g\'li',
        'Dilshod o\'g\'li',
        'Elyor o\'g\'li',
        'Farhod o\'g\'li o\'g\'li',
        'Furqat o\'g\'li',
        'Jasur o\'g\'li',
        'Kamol o\'g\'li',
        'Murod o\'g\'li',
        'Nasir o\'g\'li',
        'Nodirjon o\'g\'li',
        'Olim o\'g\'li',
        'Qosim o\'g\'li',
        'Ravshan o\'g\'li',
        'Rustam o\'g\'li',
        'Sardor o\'g\'li',
        'Sherzod o\'g\'li',
        'Toshpulat o\'g\'li',
        'Ulugbek o\'g\'li',
        'Xurshid o\'g\'li',
        'Yorqin o\'g\'li'
    ];

    private $middleNamesFemale = [
        'Abbos qizi',
        'Abdulla qizi',
        'Akbar qizi',
        'Alisher qizi',
        'Anvar qizi',
        'Asad qizi',
        'Aziz qizi',
        'Bahrom qizi',
        'Dilshod qizi',
        'Elyor qizi',
        'Farhod qizi',
        'Furqat qizi',
        'Jasur qizi',
        'Kamol qizi',
        'Murod qizi',
        'Nasir qizi',
        'Nodirjon qizi',
        'Olim qizi',
        'Qosim qizi',
        'Ravshan qizi',
        'Rustam qizi',
        'Sardor qizi',
        'Sherzod qizi',
        'Toshpulat qizi',
        'Ulugbek qizi',
        'Xurshid qizi',
        'Yorqin qizi'
    ];

    private function getUzbekFirstName(?string $gender = null): string
    {
        if ($gender === null) {
            $gender = fake()->randomElement(['male', 'female']);
        }

        return $gender === 'male'
            ? fake()->randomElement($this->firstNamesMale)
            : fake()->randomElement($this->firstNamesFemale);
    }

    private function getUzbekLastName(?string $gender = null): string
    {
        if ($gender === null) {
            $gender = fake()->randomElement(['male', 'female']);
        }

        return $gender === 'male'
            ? fake()->randomElement($this->lastNamesMale)
            : fake()->randomElement($this->lastNamesFemale);
    }

    private function getUzbekMiddleName(?string $gender = null): string
    {
        if ($gender === null) {
            $gender = fake()->randomElement(['male', 'female']);
        }

        return $gender === 'male'
            ? fake()->randomElement($this->middleNamesMale)
            : fake()->randomElement($this->middleNamesFemale);
    }

    private function generateUzbekPhoneNumber(): string
    {
        $operatorCodes = ['90', '91', '93', '94', '95', '97', '99'];
        $operatorCode = fake()->randomElement($operatorCodes);

        $localNumber = sprintf('%07d', fake()->numberBetween(0, 9999999));

        return "998{$operatorCode}" .
            substr($localNumber, 0, 3) .
            substr($localNumber, 3, 2) .
            substr($localNumber, 5, 2);
    }

    public function run(): void
    {
        for ($i = 0; $i < 300; $i++) {
            $gender = fake()->randomElement(['male', 'female']);

            $firstName = $this->getUzbekFirstName($gender);
            $lastName = $this->getUzbekLastName($gender);
            $middleName = $this->getUzbekMiddleName($gender);

            $customer = Customer::create([
                'user_id' => 1,
                'company_id' => rand(1, 5),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'middle_name' => $middleName,
                'date_of_birth' => fake()->date(),
                'phone' => $this->generateUzbekPhoneNumber(),
                'phone2' => fake()->randomElement([null, $this->generateUzbekPhoneNumber()]),
                'status' => 1,
            ]);

            CustomerDetail::create([
                'customer_id' => $customer->id,
                'region_id' => rand(1, 12),
                'city_id' => rand(1, 12),
                'district_id' => rand(1, 50),
                'neighborhood_id' => rand(1, 100),
                'home' => fake()->address(),
            ]);
        }
    }
}
