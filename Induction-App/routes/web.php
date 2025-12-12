<?php

use App\Http\Controllers\AllApplicationsController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CandidateDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CandidateProfileController;
use App\Http\Controllers\CentersAllotmentController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\InductionPhaseController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QualificationTypeController;
use App\Http\Controllers\QualificationCategoryController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\PostLevelController;
use App\Http\Controllers\permissionController;
use App\Http\Controllers\roleController;
use App\Model\District;
use App\Http\Controllers\QuotaSettingController;
use App\Http\Controllers\TestCenterController;
use App\Http\Controllers\SupportController;

Route::get('/', function () {
    return redirect('/login');
});

// Role-specific routes

// require __DIR__.'/admin.php';
Route::prefix('admin')->middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/support', [SupportController::class, 'adminIndex'])->name('admin.support.index');
    Route::get('/support/{support}', [SupportController::class, 'show'])->name('admin.support.show');
    Route::get('/payments', [\App\Http\Controllers\PaymentAdminController::class, 'index'])->name('admin.payments.index');
});

Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('provinces', ProvinceController::class);
});

Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('districts', DistrictController::class);
});

Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('cities', CitiesController::class);
});

Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('InductionPhase', InductionPhaseController::class);
});
Route::get('/districtsFetch/{province_id}', [CandidateProfileController::class, 'fetchDistricts'])->name('districts.fetch');
Route::get('/citiesFetch/{district_id}', [CandidateProfileController::class, 'getCities'])->name('cities.fetch');
Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('PostCategory', PostCategoryController::class);
});

Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('post_levels', PostLevelController::class);
});

Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('Permissions', permissionController::class);
    Route::resource('Roles', roleController::class);
});

Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('QuotaSetting', QuotaSettingController::class);
});
Route::get('induction-phase-Post', [QuotaSettingController::class, 'getByInductionPhase'])
    ->name('induction-phase-Post');
// Get Post detail
Route::get('Get-Post', [QuotaSettingController::class, 'getPostDetail'])
    ->name('Get-Post');


Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('qualification-types', QualificationTypeController::class);
});


Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('qualification-categories', QualificationCategoryController::class);
});
Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::resource('Centers', CenterController::class);
});
Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::get('/center-allotments', [CentersAllotmentController::class, 'index'])->name('center-allotments.index');
    Route::get('/center-allotments/preview', [CentersAllotmentController::class, 'preview'])->name('center-allotments.preview');
    Route::post('/center-allotments/allocate', [CentersAllotmentController::class, 'allocate'])->name('center-allotments.allocate');
    Route::get('/center-allotments/reports', [CentersAllotmentController::class, 'reports'])->name('center-allotments.reports');
        // Route::get('/postAllocate', [CentersAllotmentController::class, 'postAllocate'])->name('postAllocate');

        Route::get('/post-allocate', [CentersAllotmentController::class, 'postAllocate'])->name('post.allocate');
Route::get('/posts/for-induction/id', [CentersAllotmentController::class, 'postForInduction'])->name('posts.for.induction');
Route::post('/posts/allocate', [CentersAllotmentController::class, 'allocatePosts'])->name('posts.allocate');

Route::get('/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
Route::get('/reports/data', [\App\Http\Controllers\ReportsController::class, 'getData'])->name('reports.data');

});

// Center Wise Post Assignment routes
Route::prefix('admin/center-posts')->middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {
    Route::get('/', [\App\Http\Controllers\CenterPostAssignmentController::class, 'index'])->name('center-posts.index');
    Route::get('/cities/{city}/centers', [\App\Http\Controllers\CenterPostAssignmentController::class, 'getCentersByCity'])->name('center-posts.centers');
    Route::get('/centers/{center}/available-posts', [\App\Http\Controllers\CenterPostAssignmentController::class, 'getAvailablePosts'])->name('center-posts.available');
    Route::get('/posts/{post}/schedules', [\App\Http\Controllers\CenterPostAssignmentController::class, 'getSchedulesByPost'])->name('center-posts.schedules');
    Route::post('/assign', [\App\Http\Controllers\CenterPostAssignmentController::class, 'assignPosts'])->name('center-posts.assign');
    Route::put('/update/{centerPost}', [\App\Http\Controllers\CenterPostAssignmentController::class, 'updateAssignment'])->name('center-posts.update');
    Route::delete('/remove/{centerPost}', [\App\Http\Controllers\CenterPostAssignmentController::class, 'removeAssignment'])->name('center-posts.remove');
});
Route::middleware(['auth', 'verified',])->group(function () {
    Route::resource('applications', ApplicationController::class)->parameters([
        'applications' => 'appliedPost'
    ]);
});

Route::middleware(['auth', 'verified', 'role:candidate'])->group(function () {
    Route::get('/candidate/applications/{appliedPost}/payment', [\App\Http\Controllers\PaymentController::class, 'show'])->name('candidate.applications.payment');
    Route::post('/candidate/applications/{appliedPost}/payment/initiate', [\App\Http\Controllers\PaymentController::class, 'initiate'])->name('candidate.applications.payment.initiate');
    Route::get('/candidate/applications/{appliedPost}/challan', [\App\Http\Controllers\PaymentController::class, 'challan'])->name('candidate.applications.challan');
    Route::match(['get','post'],'/payments/1link/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payments.callback');
    Route::get('/payments/mock/redirect', [\App\Http\Controllers\PaymentController::class, 'mockRedirect'])->name('payments.mock.redirect');
    Route::get('/candidate/applications/{id}/download', [ApplicationController::class, 'download'])->name('candidate.applications.download');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/admin/support/{support}', [SupportController::class, 'adminShow'])->name('admin.support.show');
});

Route::get('/test-centers', [TestCenterController::class, 'index'])->name('test-centers.index');
Route::post('/test-centers/cities', [TestCenterController::class, 'getCities'])->name('test-centers.cities');
Route::post('/test-centers/posts', [TestCenterController::class, 'getPostsByCity'])->name('test-centers.posts');
Route::post('/test-centers/assign-posts', [TestCenterController::class, 'assignPosts'])->name('test-centers.assign-posts');
Route::post('/test-centers/update-max-applicants', [TestCenterController::class, 'updateMaxApplicants'])->name('test-centers.update-max-applicants');
Route::post('/test-centers/remove-post', [TestCenterController::class, 'removePost'])->name('test-centers.remove-post');

Route::middleware(['auth', 'verified'])->group(function () {
Route::middleware(['auth', 'verified', 'role:candidate'])->group(function () {
    Route::resource('applications', ApplicationController::class)->parameters([
        'applications' => 'appliedPost'
    ]);
});

Route::middleware(['auth', 'verified', 'role:super_admin|admin|induction'])->group(function () {

    // Main dashboard
    Route::get('/applicationsall', [AllApplicationsController::class, 'index'])
        ->name('allApplications.Index');

    // Get paginated applications with filters
    Route::get('/api/applications', [AllApplicationsController::class, 'getApplications'])
        ->name('api.applications.index');

    // Show single application
    Route::get('/api/applications/{id}', [AllApplicationsController::class, 'show'])
        ->name('api.applications.show');

    // Update single application status
    Route::patch('/api/applications/{id}/status', [AllApplicationsController::class, 'updateStatus'])
        ->name('api.applications.updateStatus');

    // Bulk operations with rate limiting
    Route::post('/api/applications/bulk-update', [AllApplicationsController::class, 'bulkUpdate'])
        ->middleware('throttle:10,1') // 10 requests per minute
        ->name('api.applications.bulkUpdate');

    Route::post('/api/applications/bulk-delete', [AllApplicationsController::class, 'bulkDelete'])
        ->middleware('throttle:5,1') // 5 requests per minute (more restrictive)
        ->name('api.applications.bulkDelete');

    // Delete single application
    Route::delete('/api/applications/{id}', [AllApplicationsController::class, 'destroy'])
        ->middleware('throttle:20,1')
        ->name('api.applications.destroy');

    // Export applications
    Route::get('/api/applications/export', [AllApplicationsController::class, 'export'])
        ->middleware('throttle:5,10') // 5 exports per 10 minutes
        ->name('api.applications.export');
});

Route::middleware(['auth', 'verified', 'role:candidate'])->group(function () {
    Route::controller(CandidateProfileController::class)->group(function () {
        Route::get('/Candidate/profile',  'Profile')->name('Candidate.Profile');
        Route::post('/Candidate/profile/update', 'update')->name('candidate.profile.update');

        Route::post('/Candidate/profile/ageRelaxation', 'updateAgeRelaxation')->name('candidate.ageRelaxation.update');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('support', SupportController::class)->names('Support');
    Route::post('support/{support}/messages', [SupportController::class, 'addMessage'])->middleware('throttle:15,1')->name('Support.messages.store');
    Route::get('support/{support}/messages', [SupportController::class, 'messages'])->name('Support.messages.index');
});

Route::get('Reply-Support', [SupportController::class, 'Support_ticket'])->name('Support.Reply');

Route::prefix('candidate')->middleware(['auth', 'verified', 'role:candidate'])->group(function () {

    Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('candidate.dashboard');
    Route::post('/apply', [CandidateDashboardController::class, 'apply'])->middleware('throttle:6,1')->name('candidate.apply');
});

Route::post('/candidate/qualification/store', [CandidateProfileController::class, 'storeQualification'])
    ->name('candidate.qualification.store');

Route::delete('/candidate/qualification/{id}', [CandidateProfileController::class, 'destroyQualification'])
    ->name('candidate.qualification.destroy');

// Keep existing route for bulk update if needed
Route::post('/candidate/qualifications/update', [CandidateProfileController::class, 'updateQualifications'])
    ->name('candidate.qualifications.update');

// routes/web.php
Route::get('/profile/status', function () {
    $lastUpdate = Cache::get('last_profile_update_' . auth()->id());
    return response()->json([
        'updated' => $lastUpdate && $lastUpdate > now()->subMinute()
    ]);
});

// require __DIR__.'/candidate.php';
require __DIR__ . '/induction.php';
require __DIR__ . '/dashboard.php';

Route::get('/flush-sessions', function () {
    Session::flush();
    return "Sessions cleared successfully!";
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
});
require __DIR__ . '/auth.php';
