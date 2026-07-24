<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'sardar@gmail.com')->first();
if ($user) {
    $user->password = Hash::make('password');
    $user->save();
    echo "PASSWORD_RESET_SUCCESS\n";
} else {
    $user = App\Models\User::create([
        'name' => 'Sardar',
        'email' => 'sardar@gmail.com',
        'password' => Hash::make('password'),
    ]);
    echo "USER_CREATED_AND_PASSWORD_SET\n";
}
