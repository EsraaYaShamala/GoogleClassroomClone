<?php

use App\Http\Controllers\Admin\TwoFactorAuthenticationController;
use App\Http\Controllers\AssignmentsController;
use App\Http\Controllers\ClassroomPeopleController;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JoinClassroomController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\WebHooks\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::prefix('/classrooms/trashed')->controller(ClassroomsController::class)->name('classrooms.')->group(function () {
        Route::get('/', 'trashed')->name('trashed');
        Route::put('/{classroom}', 'restore')->name('restore');
        Route::delete('/{classroom}', 'forceDelete')->name('force-delete');
    });
    Route::prefix('classrooms/{classroom}/topics/trashed')->controller(TopicsController::class)->as('topics.')->group(function () {
        Route::get('/', 'trashed')->name('trashed');
        Route::put('/{topic}', 'restore')->name('restore');
        Route::delete('/{topic}', 'forceDelete')->name('force-delete');
    });
    Route::get('/classrooms/{classroom}/join', [JoinClassroomController::class, 'create'])
        ->middleware('signed')->name('classrooms.join');
    Route::post('/classrooms/{classroom}/join', [JoinClassroomController::class, 'store']);
    Route::resources([
        'classrooms' => ClassroomsController::class,
        'classrooms.topics' => TopicsController::class,
        'classrooms.classworks' => ClassworkController::class,
        'classrooms.posts' => PostController::class,
    ]);

    Route::get('/classrooms/{classroom}/people', [ClassroomPeopleController::class, 'index'])
        ->name('classrooms.people');
    Route::delete('/classrooms/{classroom}/people', [ClassroomPeopleController::class, 'destroy'])
        ->name('classrooms.people.destroy');

    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('classwork/{classwork}/submission', [SubmissionController::class, 'store'])
        ->name('submissions.store');
    Route::get('submissions/{submission}', [SubmissionController::class, 'file'])->name('submissions.file');

    Route::post('subscriptions', [SubscriptionsController::class, 'store'])->name('subscriptions.store');
    Route::post('payments', [PaymentsController::class, 'store'])->name('payments.store');
    Route::get('subscriptions/{subscription}/pay', [PaymentsController::class, 'create'])->name('checkout');
    Route::get('payments/{subscription}/success', [PaymentsController::class, 'success'])->name('payments.success');
    Route::get('payments/{subscription}/cancel', [PaymentsController::class, 'cancel'])->name('payments.cancel');
});

Route::get('plans', [PlanController::class, 'index'])->name('plans');
Route::post('/payments/stripe/webhook', StripeController::class);

Route::get('/admin/2fa', [TwoFactorAuthenticationController::class, 'create'])->name('2fa.create');
