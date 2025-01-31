<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run()
    {
        // 1. Toshkent shahri (Tashkent City)
        $tashkentCity = Region::create([
            'name' => 'Toshkent shahri',
            'status' => true,
        ]);

        $tashkentCityDistricts = [
            'Bektemir tumani',
            'Chilonzor tumani',
            'Mirobod tumani',
            'Mirzo Ulugʻbek tumani',
            'Olmazor tumani',
            'Sergeli tumani',
            'Shayxontohur tumani',
            'Uchtepa tumani',
            'Yakkasaroy tumani',
            'Yangihayot tumani',
            'Yunusobod tumani',
        ];

        foreach ($tashkentCityDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $tashkentCity->id
            ]);
        }

        // 2. Toshkent viloyati (Tashkent Region)
        $tashkentRegion = Region::create([
            'name' => 'Toshkent viloyati',
            'status' => true,
        ]);

        $tashkentRegionDistricts = [
            'Bekobod tumani',
            'Boʻstonliq tumani',
            'Boʻka tumani',
            'Chinoz tumani',
            'Qibray tumani',
            'Ohangaron tumani',
            'Oqqoʻrgʻon tumani',
            'Parkent tumani',
            'Piskent tumani',
            'Quyi Chirchiq tumani',
            'Oʻrta Chirchiq tumani',
            'Yangiyoʻl tumani',
            'Yuqori Chirchiq tumani',
            'Zangiota tumani'
        ];

        $tashkentRegionCities = [
            'Angren',
            'Bekobod',
            'Chirchiq',
            'Olmaliq',
            'Ohangaron',
            'Yangiyoʻl',
            'Nurafshon'
        ];

        foreach ($tashkentRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $tashkentRegion->id
            ]);
        }

        foreach ($tashkentRegionCities as $cityName) {
            City::create([
                'name' => $cityName,
                'status' => true,
                'region_id' => $tashkentRegion->id
            ]);
        }

        // 3. Andijon viloyati (Andijan Region)
        $andijanRegion = Region::create([
            'name' => 'Andijon viloyati',
            'status' => true,
        ]);

        $andijanRegionDistricts = [
            'Andijon tumani',
            'Asaka tumani',
            'Baliqchi tumani',
            'Boʻz tumani',
            'Buloqboshi tumani',
            'Izboskan tumani',
            'Jalaquduq tumani',
            'Xoʻjaobod tumani',
            'Qoʻrgʻontepa tumani',
            'Marhamat tumani',
            'Oltinkoʻl tumani',
            'Paxtaobod tumani',
            'Shahrixon tumani',
            'Ulugʻnor tumani'
        ];

        $andijanRegionCities = [
            'Andijon',
            'Asaka',
            'Xonobod'
        ];

        foreach ($andijanRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $andijanRegion->id
            ]);
        }

        foreach ($andijanRegionCities as $cityName) {
            City::create([
                'name' => $cityName,
                'status' => true,
                'region_id' => $andijanRegion->id
            ]);
        }

        // 4. Buxoro viloyati (Bukhara Region)
        $bukharaRegion = Region::create([
            'name' => 'Buxoro viloyati',
            'status' => true,
        ]);

        $bukharaRegionDistricts = [
            'Buxoro tumani',
            'Gʻijduvon tumani',
            'Jondor tumani',
            'Kogon tumani',
            'Qorakoʻl tumani',
            'Qorovulbozor tumani',
            'Olot tumani',
            'Peshku tumani',
            'Romitan tumani',
            'Shofirkon tumani',
            'Vobkent tumani'
        ];

        $bukharaRegionCities = [
            'Buxoro',
            'Kogon',
            'Gʻijduvon'
        ];

        foreach ($bukharaRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $bukharaRegion->id
            ]);
        }

        foreach ($bukharaRegionCities as $cityName) {
            City::create([
                'name' => $cityName . ' shahri',
                'status' => true,
                'region_id' => $bukharaRegion->id
            ]);
        }

        // 5. Fargʻona viloyati (Fergana Region)
        $ferganaRegion = Region::create([
            'name' => 'Fargʻona viloyati',
            'status' => true,
        ]);

        $ferganaRegionDistricts = [
            'Oltiariq tumani',
            'Bagʻdod tumani',
            'Beshariq tumani',
            'Buvayda tumani',
            'Dangʻara tumani',
            'Fargʻona tumani',
            'Furqat tumani',
            'Qoʻshtepa tumani',
            'Quva tumani',
            'Rishton tumani',
            'Soʻx tumani',
            'Toshloq tumani',
            'Uchkoʻprik tumani',
            'Oʻzbekiston tumani',
            'Yozyovon tumani'
        ];

        $ferganaRegionCities = [
            'Fargʻona',
            'Qoʻqon',
            'Quvasoy',
            'Margʻilon'
        ];

        foreach ($ferganaRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $ferganaRegion->id
            ]);
        }

        foreach ($ferganaRegionCities as $cityName) {
            City::create([
                'name' => $cityName,
                'status' => true,
                'region_id' => $ferganaRegion->id
            ]);
        }

        // 6. Jizzax viloyati (Jizzakh Region)
        $jizzakhRegion = Region::create([
            'name' => 'Jizzax viloyati',
            'status' => true,
        ]);

        $jizzakhRegionDistricts = [
            'Arnasoy tumani',
            'Baxmal tumani',
            'Doʻstlik tumani',
            'Forish tumani',
            'Gʻallaorol tumani',
            'Sharof Rashidov tumani',
            'Mirzachoʻl tumani',
            'Paxtakor tumani',
            'Yangiobod tumani',
            'Zomin tumani',
            'Zafarobod tumani',
            'Zarbdor tumani'
        ];

        $jizzakhRegionCities = [
            'Jizzax',
            'Gagarin'
        ];

        foreach ($jizzakhRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $jizzakhRegion->id
            ]);
        }

        foreach ($jizzakhRegionCities as $cityName) {
            City::create([
                'name' => $cityName,
                'status' => true,
                'region_id' => $jizzakhRegion->id
            ]);
        }

        // 7. Xorazm viloyati (Khorezm Region)
        $khorezmRegion = Region::create([
            'name' => 'Xorazm viloyati',
            'status' => true,
        ]);

        $khorezmRegionDistricts = [
            'Bogʻot tumani',
            'Gurlan tumani',
            'Xonqa tumani',
            'Hazorasp tumani',
            'Xiva tumani',
            'Qoʻshkoʻpir tumani',
            'Shovot tumani',
            'Urganch tumani',
            'Yangiariq tumani',
            'Yangibozor tumani'
        ];

        $khorezmRegionCities = [
            'Urganch',
            'Xiva',
            'Pitnak'
        ];

        foreach ($khorezmRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $khorezmRegion->id
            ]);
        }

        foreach ($khorezmRegionCities as $cityName) {
            City::create([
                'name' => $cityName,
                'status' => true,
                'region_id' => $khorezmRegion->id
            ]);
        }

        // 8. Namangan viloyati (Namangan Region)
        $namanganRegion = Region::create([
            'name' => 'Namangan viloyati',
            'status' => true,
        ]);

        $namanganRegionDistricts = [
            'Chortoq tumani',
            'Chust tumani',
            'Kosonsoy tumani',
            'Mingbuloq tumani',
            'Namangan tumani',
            'Norin tumani',
            'Pop tumani',
            'Toʻraqoʻrgʻon tumani',
            'Uchqoʻrgʻon tumani',
            'Uychi tumani',
            'Yangiqoʻrgʻon tumani'
        ];

        $namanganRegionCities = [
            'Namangan',
            'Chortoq',
            'Chust',
            'Pop'
        ];

        foreach ($namanganRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $namanganRegion->id
            ]);
        }

        foreach ($namanganRegionCities as $cityName) {
            City::create([
                'name' => $cityName,
                'status' => true,
                'region_id' => $namanganRegion->id
            ]);
        }

        // 9. Navoiy viloyati (Navoi Region)
        $navoiRegion = Region::create([
            'name' => 'Navoiy viloyati',
            'status' => true,
        ]);

        $navoiRegionDistricts = [
            'Xatirchi tumani',
            'Konimex tumani',
            'Karmana tumani',
            'Qiziltepa tumani',
            'Navbahor tumani',
            'Nurota tumani',
            'Tamdy tumani',
            'Uchquduq tumani'
        ];

        $navoiRegionCities = [
            'Navoiy',
            'Zarafshon',
        ];

        $xatirchiNeighborhoods = [
            'Yangirabod',
            'Chinobod',
            'Shirinobod',
            'Samarqand',
            'Damariq',
            'Chechakota',
        ];

        foreach ($navoiRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $navoiRegion->id
            ]);
        }

        foreach ($navoiRegionCities as $cityName) {
            City::create([
                'name' => $cityName . ' shahar',
                'status' => true,
                'region_id' => $navoiRegion->id
            ]);
        }

        foreach ($xatirchiNeighborhoods as $neighborhoodName) {
            Neighborhood::create([
                'name' => $neighborhoodName . ' MFY',
                'status' => true,
                'district_id' => $navoiRegion->districts()->first()->id,
            ]);
        }

        // 10. Qashqadaryo viloyati (Kashkadarya Region)
        $kashkadaryaRegion = Region::create([
            'name' => 'Qashqadaryo viloyati',
            'status' => true,
        ]);

        $kashkadaryaRegionDistricts = [
            'Chiroqchi tumani',
            'Dehqonobod tumani',
            'Gʻuzor tumani',
            'Qamashi tumani',
            'Qarshi tumani',
            'Koson tumani',
            'Kasbi tumani',
            'Kitob tumani',
            'Mirishkor tumani',
            'Muborak tumani',
            'Nishon tumani',
            'Shahrisabz tumani',
            'Yakkabogʻ tumani'
        ];

        $kashkadaryaRegionCities = [
            'Qarshi',
            'Shahrisabz',
            'Kitob'
        ];

        foreach ($kashkadaryaRegionDistricts as $districtName) {
            District::create([
                'name' => $districtName,
                'status' => true,
                'region_id' => $kashkadaryaRegion->id
            ]);
        }

        foreach ($kashkadaryaRegionCities as $cityName) {
            City::create([
                'name' => $cityName  . ' shahar',
                'status' => true,
                'region_id' => $kashkadaryaRegion->id
            ]);
        }

        // 11. Qoraqalpogʻiston Respublikasi (Republic of Karakalpakstan)
        $karakalpakstanRegion = Region::create([
            'name' => 'Qoraqalpogʻiston Respublikasi',
            'status' => true,
        ]);
    }
}
