<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Review;

// Find buyer
$buyer = User::where('email', 'buyer@tokokita.com')->first();

if ($buyer) {
    // Delete all reviews by Eko Prasetyo to ensure his completed orders are unreviewed
    Review::where('buyer_id', $buyer->id)->delete();
    echo "SUCCESS: Deleted all reviews for buyer to prepare test order.\n";
} else {
    echo "ERROR: Buyer not found.\n";
}
