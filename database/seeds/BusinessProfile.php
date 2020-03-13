<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Model\Business;

class BusinessProfile extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
       // $user = Business::from('business_profile as b')->get();
       // foreach($user as $row) {
       //   for ($x = 1; $x <= 7; $x++) {
       //     if($x == 7) {
       //       $ins = ["profile_id" => $row->id , "start_time" => "00:00:00", "end_time" => "00:00:00", "is_off" => 1, "day_id" => $x];
       //     } else {
       //       $ins = ["profile_id" => $row->id , "start_time" => "10:00:00", "end_time" => "22:00:00", "is_off" => 0, "day_id" => $x];
       //     }
       //     DB::table('working_hours')->insert($ins);
       //  }
       // }
     }
    // public function run()
    // {
    //     for ($x = 0; $x <= 2500; $x++) {
    //     $faker = Faker::create('App\User');
    //     $mobile = str_replace('+','',$faker->unique()->phoneNumber);
    //     $mobile = str_replace('-','',$mobile);
    //     $mobile = substr($mobile, -10);
    //     $user = [
    //       'first_name' => $faker->name,
    //       'email' => $faker->unique()->safeEmail,
    //       'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    //       'mobilecode' => 91,
    //       'mobile' => $mobile,
    //       'country_id' => 101,
    //       'device_id' => $faker->randomNumber($nbDigits = NULL),
    //       'fcm_token' => $faker->randomNumber($nbDigits = NULL),
    //       'device_type' => 1
    //     ];
    //     $user = Sentinel::registerAndActivate($user);
    //     $role = Sentinel::findRoleBySlug('business');
    //     $role->users()->attach($user);
    //
    //     $faker = Faker::create('App\Model\Business');
    //     $dir = "image/faker/logo/";
    //     $image_no = rand(1,26);
    //     if($image_no <= 10) {
    //       $image_path =  $image_no.".jpg";
    //     } else {
    //       $image_path =  $image_no.".png";
    //     }
    //     $image_path = $dir.$image_path;
    //
    //     //category
    //     $category = rand(1,3);
    //     if($category == 1) {
    //       $subcategory = rand(4,5);
    //     } elseif($category == 2) {
    //       $subcategory = rand(6,7);
    //     } else {
    //       $subcategory = rand(8,10);
    //     }
    //
    //     //$plan_id
    //     $plan_id = rand(1,2);
    //     DB::table('business_profile')->insert([
    //       "user_id" => $user->id,
    //       "name" => $faker->name,
    //       "logo" => $image_path,
    //       "category_id1" => $category,
    //       "category_id2" => $subcategory,
    //       "country" => 101,
    //       "state" => 12,
    //       "city" => rand(779,1095),
    //       "address" => $faker->address,
    //       "pincode" => $faker->postcode,
    //       "latitude" => $faker->latitude(),
    //       "longitude" => $faker->longitude(),
    //       "plan_id" => $plan_id,
    //       "start_date" => date('Y-m-d',strtotime("now")),
    //       "end_date" => date('Y-m-d',strtotime('+'.$plan_id.' years')),
    //       "gst_no" => "",
    //       "status" => 1
    //     ]);
    //   }
    // }
}
