<?php

use App\Livewire\Frontend\ProposalSignPage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/admin');
});

// Public proposal signing route

Route::get('/proposal/sign/{proposal}', ProposalSignPage::class)
    ->name('frontend.proposal.sign');


Route::get('/test-mail', function () {
    Mail::raw('Test Email', function ($message) {
        $message->to('notesharbourgju@gmail.com')
                ->subject('Test Mail');
    });

    return 'Mail sent';
});
