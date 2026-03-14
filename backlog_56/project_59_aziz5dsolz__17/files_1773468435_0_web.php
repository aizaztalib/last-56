<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BacklogController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserBacklogController;
use App\Http\Controllers\User\UserProjectController;
use App\Http\Controllers\User\UserVotingController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CoinDistributionController;
use App\Http\Controllers\User\UserCoinDistributionController;
use App\Http\Controllers\EthereumTransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GitHubController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test-email', function () {
    try {
        Mail::raw('Test email from Laravel', function ($message) {
            $message->to('muhammadmukhshif123@gmail.com')
                ->subject('Laravel Mail Test');
        });
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
Route::get('/', [AdminController::class, 'index'])->name('login');
Route::get('/login', [AdminController::class, 'index'])->name('login');
Route::get('/signup', [AdminController::class, 'signup'])->name('signup');
Route::post('/signupSubmit', [AdminController::class, 'signupSubmit'])->name('signupSubmit');
Route::post('/loginSubmit', [AdminController::class, 'loginSubmit'])->name('loginSubmit');
//==============================logout ===========================================//
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

// Send OTP to Email
Route::post('/forgot-password/send-otp', [AdminController::class, 'sendOtp'])->name('password.sendOtp');

// Verify OTP
Route::post('/forgot-password/verify-otp', [AdminController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::post('/reset-password', [AdminController::class, 'resetPassword'])->name('resetPassword');

Route::get('auth/google', [AdminController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AdminController::class, 'handleGoogleCallback']);

//
Route::get('createRepo', [BacklogController::class, 'createRepo']);

// admin
Route::middleware(['auth', 'role:0|1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/notification', [AdminController::class, 'notification'])->name('notification');

    //================ Users =================//
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/getUsers', [UserController::class, 'getUsers'])->name('getUsers');
    Route::post('/changeUserStatus', [UserController::class, 'changeUserStatus'])->name('changeUserStatus');
    Route::post('/saveUser', [UserController::class, 'saveUser'])->name('saveUser');
    Route::post('/editUser', [UserController::class, 'editUser'])->name('editUser');

    //================== Profile ====================//
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/getProfileData', [ProfileController::class, 'getProfileData'])->name('getProfileData');
    Route::post('/editProfile', [ProfileController::class, 'editProfile'])->name('editProfile');
    Route::post('/saveProfile', [ProfileController::class, 'saveProfile'])->name('saveProfile');


    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    // Route::post('/getProfileData', [ProfileController::class, 'getProfileData'])->name('getProfileData');
    
    // Add this new route for the modal profile view
    Route::post('/getUserProfileForModal', [ProfileController::class, 'getUserProfileForModal'])->name('getUserProfileForModal');
    
    

    Route::post('/editAdditionalInfo', [ProfileController::class, 'editAdditionalInfo'])->name('editAdditionalInfo');
    Route::post('/saveUserAdditional', [ProfileController::class, 'saveUserAdditional'])->name('saveUserAdditional');
    Route::post('/saveUserQualification', [ProfileController::class, 'saveUserQualification'])->name('saveUserQualification');
    Route::post('/saveUserSkills', [ProfileController::class, 'saveUserSkills'])->name('saveUserSkills');
    Route::post('/saveUserDocument', [ProfileController::class, 'saveUserDocument'])->name('saveUserDocument');
    Route::post('/deleteUserDocument', [ProfileController::class, 'deleteUserDocument'])->name('deleteUserDocument');

    Route::post('/markNotificationAsRead', [ProfileController::class, 'markNotificationAsRead'])->name('markNotificationAsRead');
    Route::post('/deleteUserNotification', [ProfileController::class, 'deleteUserNotification'])->name('deleteUserNotification');

    Route::post('/saveInternalNote', [ProfileController::class, 'saveInternalNote'])->name('saveInternalNote');
    Route::post('/deleteUserNote', [ProfileController::class, 'deleteUserNote'])->name('deleteUserNote');
    Route::post('/saveUserProfilePicture', [ProfileController::class, 'saveUserProfilePicture'])->name('saveUserProfilePicture');

    Route::post('/editAccountInfo', [ProfileController::class, 'editAccountInfo'])->name('editAccountInfo');
    Route::post('/saveAccountInfo', [ProfileController::class, 'saveAccountInfo'])->name('saveAccountInfo');

    //========== backlogs ==========//
    Route::get('/backlogs', [BacklogController::class, 'index'])->name('backlogs');
    Route::post('/saveBacklogs', [BacklogController::class, 'saveBacklogs'])->name('saveBacklogs');
    Route::post('/getBacklogs', [BacklogController::class, 'getBacklogs'])->name('getBacklogs');
    Route::post('/confirmBacklogs', [BacklogController::class, 'confirmBacklogs'])->name('confirmBacklogs');
    Route::post('/backlogsRejectDone', [BacklogController::class, 'backlogsRejectDone'])->name('backlogsRejectDone');
    Route::post('/doneBacklogs', [BacklogController::class, 'doneBacklogs'])->name('doneBacklogs');
    Route::post('/rejectPendingProjects', [BacklogController::class, 'rejectPendingProjects'])->name('rejectPendingProjects');
    Route::post('/viewBacklog', [BacklogController::class, 'viewBacklog'])->name('viewBacklog');
    Route::get('/backlog/{id}/github-access', [BacklogController::class, 'grantGitHubAccess'])->name('backlog.github.access');
    Route::post('/collaborators/add', [BacklogController::class, 'addCollaborator']);
    Route::post('/getHistoryLogs', [BacklogController::class, 'getHistoryLogs'])->name('getHistoryLogs');

    


    //new 
    Route::post('/getBacklogFiles', [BacklogController::class, 'getBacklogFiles'])->name('admin.getBacklogFiles');
    Route::post('/getBacklogFileContent', [BacklogController::class, 'getBacklogFileContent'])->name('admin.getBacklogFileContent');
    Route::post('/getLocalBacklogFiles', [BacklogController::class, 'getLocalBacklogFiles'])->name('admin.getLocalBacklogFiles');
    Route::post('/getLocalFileContent', [BacklogController::class, 'getLocalFileContent'])->name('admin.getLocalFileContent');

    // GitHub repository routes
    Route::post('/getAdminRepositoryContents', [BacklogController::class, 'getAdminRepositoryContents'])->name('admin.getAdminRepositoryContents');
    Route::post('/getAdminRepositoryBranches', [BacklogController::class, 'getAdminRepositoryBranches'])->name('admin.getAdminRepositoryBranches');
    Route::post('/getAdminFileContent', [BacklogController::class, 'getAdminFileContent'])->name('admin.getAdminFileContent');

    //========== projects ==========//
    Route::get('/projects/{id?}', [ProjectController::class, 'index'])->name('projects');
    Route::post('/getProject', [ProjectController::class, 'getProject'])->name('getProject');
    Route::post('/statusProject', [ProjectController::class, 'statusProject'])->name('statusProject');
    Route::post('/confirmedApprovedProject', [ProjectController::class, 'confirmedApprovedProject'])->name('confirmedApprovedProject');
    Route::post('/viewProject', [ProjectController::class, 'viewProject'])->name('viewProject');
    Route::post('/viewVoterList', [ProjectController::class, 'viewVoterList'])->name('viewVoterList');
    Route::post('/getProjectCounts', [ProjectController::class, 'getProjectCounts'])->name('getProjectCounts');


    Route::post('/getProjectFiles', [ProjectController::class, 'getProjectFiles'])->name('admin.getProjectFiles');


    //========== voting ==========//
    Route::get('/voting', [VotingController::class, 'index'])->name('voting');
    Route::post('/getVoting', [VotingController::class, 'getVoting'])->name('getVoting');
    Route::post('/deleteVote', [VotingController::class, 'deleteVote'])->name('deleteVote');

    //========== coin ==========//
    Route::get('/coin_distribution', [CoinDistributionController::class, 'index'])->name('coin_distribution');
    Route::post('/addPayment', [CoinDistributionController::class, 'addPayment'])->name('addPayment');
    Route::post('/getCoinDistribution', [CoinDistributionController::class, 'getCoinDistribution'])->name('getCoinDistribution');
    Route::post('/distributionStatus', [CoinDistributionController::class, 'distributionStatus'])->name('distributionStatus');
    Route::post('/paymentDetails', [CoinDistributionController::class, 'paymentDetails'])->name('paymentDetails');

    Route::get('/getWalletData', [CoinDistributionController::class, 'getWalletData'])->name('admin.getWalletData');
    

    Route::post('/getPendingAmountBacklogList', [CoinDistributionController::class, 'getPendingAmountBacklogList'])->name('getPendingAmountBacklogList');

    //========== Ethereum Transactions ==========//
    Route::get('/ethereum/transactions', [EthereumTransactionController::class, 'listTransactions'])->name('ethereum.transactions.index');
    Route::get('/ethereum/transactions/{id}', [EthereumTransactionController::class, 'getTransaction'])->name('ethereum.transactions.show');
    Route::post('/ethereum/transactions/{id}/retry', [EthereumTransactionController::class, 'retryTransaction'])->name('ethereum.transactions.retry');
    Route::post('/ethereum/transactions/{id}/verify', [EthereumTransactionController::class, 'verifyTransaction'])->name('ethereum.transactions.verify');

    //================== Support ====================//
    Route::post('/getSupportPageData', [SupportController::class, 'getSupportPageData'])->name('getSupportPageData');
    Route::post('/viewTicket', [SupportController::class, 'viewTicket'])->name('viewTicket');
    Route::post('/saveTicket', [SupportController::class, 'saveTicket'])->name('saveTicket');
    Route::post('/saveTicketReply', [SupportController::class, 'saveTicketReply'])->name('saveTicketReply');
    Route::post('/markTicketStatus ', [SupportController::class, 'markTicketStatus'])->name('markTicketStatus');



    //===========Setting =============//
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/savePaymentSetting', [SettingController::class, 'savePaymentSetting'])->name('savePaymentSetting');


    // Route::get('/profile', [SettingController::class, 'index'])->name('profile');

    // Route::get('/profile', function () {
    //     return view('admin.profile');
    // })->name('profile');

    // Route::get('/notification', function () {
    //     return view('admin.notification');
    // })->name('notification');

    Route::get('/role-permission', function () {
        return view('admin.roles_permission');
    })->name('role-permission');

    Route::get('/support', function () {
        return view('admin.support');
    })->name('support');
});

// user
Route::middleware(['auth', 'role:2'])->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/getUsers', [UserController::class, 'getUsers'])->name('getUsers');
    Route::get('/notification', [UserController::class, 'notification'])->name('notification');
    Route::get('/support', [SupportController::class, 'index'])->name('support');

    // Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::get('/profile', function () {

        return view('user.profile');
    })->name('profile');

    //================== Profile ====================//
    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/getProfileData', [ProfileController::class, 'getProfileData'])->name('getProfileData');
    Route::get('/profile/{id}', [ProfileController::class, 'showProfile'])->name('user.profile');
    Route::post('/editProfile', [ProfileController::class, 'editProfile'])->name('editProfile');
    Route::post('/saveProfile', [ProfileController::class, 'saveProfile'])->name('saveProfile');


    

    Route::post('/editAdditionalInfo', [ProfileController::class, 'editAdditionalInfo'])->name('editAdditionalInfo');
    Route::post('/saveUserAdditional', [ProfileController::class, 'saveUserAdditional'])->name('saveUserAdditional');
    Route::post('/saveUserQualification', [ProfileController::class, 'saveUserQualification'])->name('saveUserQualification');
    Route::post('/saveUserSkills', [ProfileController::class, 'saveUserSkills'])->name('saveUserSkills');
    Route::post('/saveUserDocument', [ProfileController::class, 'saveUserDocument'])->name('saveUserDocument');
    Route::post('/deleteUserDocument', [ProfileController::class, 'deleteUserDocument'])->name('deleteUserDocument');

    Route::post('/markNotificationAsRead', [ProfileController::class, 'markNotificationAsRead'])->name('markNotificationAsRead');
    Route::post('/deleteUserNotification', [ProfileController::class, 'deleteUserNotification'])->name('deleteUserNotification');

    Route::post('/saveInternalNote', [ProfileController::class, 'saveInternalNote'])->name('saveInternalNote');
    Route::post('/deleteUserNote', [ProfileController::class, 'deleteUserNote'])->name('deleteUserNote');
    Route::post('/saveUserProfilePicture', [ProfileController::class, 'saveUserProfilePicture'])->name('saveUserProfilePicture');

    Route::post('/editAccountInfo', [ProfileController::class, 'editAccountInfo'])->name('editAccountInfo');
    Route::post('/saveAccountInfo', [ProfileController::class, 'saveAccountInfo'])->name('saveAccountInfo');

    //========== backlogs ==========//
    Route::get('/backlogs', [UserBacklogController::class, 'index'])->name('backlogs');
    Route::post('/saveBacklogs', [UserBacklogController::class, 'saveBacklogs'])->name('saveBacklogs');
    Route::post('/getBacklogs', [UserBacklogController::class, 'getBacklogs'])->name('getBacklogs');
    Route::post('/viewBacklog', [UserBacklogController::class, 'viewBacklog'])->name('viewBacklog');
    Route::post('/deleteBacklog', [UserBacklogController::class, 'deleteBacklog'])->name('deleteBacklog');
    Route::post('/editBacklogs', [UserBacklogController::class, 'editBacklogs'])->name('editBacklogs');
    Route::post('/user/reject-backlog', [UserBacklogController::class, 'rejectBacklog'])->name('user.reject.backlog');
    Route::post('/collaborators/add', [UserBacklogController::class, 'addCollaborator']);

    // new 
    Route::post('/repository/contents', [UserBacklogController::class, 'getRepositoryContents'])->name('repository.contents');
    Route::post('/repository/file-content', [UserBacklogController::class, 'getFileContent'])->name('repository.file-content');
    Route::post('/repository/branches', [UserBacklogController::class, 'getRepositoryBranches'])->name('repository.branches');

    //========== project ==========//
    Route::get('/projects/{id?}', [UserProjectController::class, 'index'])->name('projects');
    Route::post('/saveProject', [UserProjectController::class, 'saveProject'])->name('saveProject');
    Route::post('/getProject', [UserProjectController::class, 'getProject'])->name('getProject');
    Route::post('/viewProject', [UserProjectController::class, 'viewProject'])->name('viewProject');
    Route::post('/user/reject-project', [UserProjectController::class, 'rejectProject'])->name('user.reject.project');


    // Add this to your user routes
    Route::post('/getProjectFiles', [UserProjectController::class, 'getProjectFiles'])->name('user.getProjectFiles');

    // =============== Vote ===========//
    Route::post('/submitVote', [UserProjectController::class, 'submitVote'])->name('submitVote');
    Route::post('/viewVoterList', [UserProjectController::class, 'viewVoterList'])->name('viewVoterList');

    Route::get('/voting', [UserVotingController::class, 'index'])->name('voting');
    Route::post('/getVoting', [UserVotingController::class, 'getVoting'])->name('getVoting');


    Route::get('/coin_distribution', [UserCoinDistributionController::class, 'index'])->name('coin_distribution');
    Route::post('/getCoinDistribution', [UserCoinDistributionController::class, 'getCoinDistribution'])->name('getCoinDistribution');


    Route::get('/getUserWalletData', [UserCoinDistributionController::class, 'getUserWalletData'])->name('user.getUserWalletData');
    
    //========== Wallet Management ==========//
    Route::post('/wallet/link', [WalletController::class, 'linkWallet'])->name('wallet.link');
    Route::post('/wallet/verify', [WalletController::class, 'verifyWallet'])->name('wallet.verify');
    Route::get('/wallet', [WalletController::class, 'getUserWallet'])->name('wallet.get');
    Route::get('/wallet/verification-message', [WalletController::class, 'getVerificationMessage'])->name('wallet.verification-message');
    Route::post('/wallet/update-balance', [WalletController::class, 'updateBalance'])->name('wallet.update-balance');
    Route::post('/wallet/unlink', [WalletController::class, 'unlinkWallet'])->name('wallet.unlink');

    //================== Support ====================//
    Route::post('/getSupportPageData', [SupportController::class, 'getSupportPageData'])->name('getSupportPageData');
    Route::post('/viewTicket', [SupportController::class, 'viewTicket'])->name('viewTicket');
    Route::post('/saveTicket', [SupportController::class, 'saveTicket'])->name('saveTicket');
    Route::post('/saveTicketReply', [SupportController::class, 'saveTicketReply'])->name('saveTicketReply');
    Route::post('/markTicketStatus ', [SupportController::class, 'markTicketStatus'])->name('markTicketStatus');

    // Route::get('/support', function () {
    //     return view('user.support');
    // })->name('support');



    // Route::get('/notification', function () {
    //     return view('user.notification');
    // })->name('notification');
});


// Route::middleware(['auth'])->group(function () {
//     Route::get('/user/getCommunityMembers', [UserController::class, 'getCommunityMembers']);
// });




// GitHub OAuth routes

Route::get('/auth/github', [AuthController::class, 'redirectToGitHub'])->name('auth.github.redirect');
Route::get('/auth/github/callback', [AuthController::class, 'handleGitHubCallback'])->name('auth.github.callback');
Route::get('/auth/github/link', [AuthController::class, 'linkGitHubAccount'])->name('auth.github.link');
Route::get('/auth/github/link/callback', [AuthController::class, 'handleGitHubLinking'])->name('auth.github.link.callback');
Route::get('/repositories', [GitHubController::class, 'createRepository']);

Route::get('/collaborators/remove', [GitHubController::class, 'removeCollaborator']);

// Method 1: Using Eloquent Model (assuming you have a User model)

Route::get('/test', function () {
    // Create admin user with status 0
    $admin = User::create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@example.com',
        'password' => Hash::make('Admin123#'), // Always hash passwords
        'role' => '0', // Admin role
        'status' => '0', // Inactive status
        'email_verified_at' => now(), // Optional: mark as verified
    ]);
});

// Ethereum RPC Connection Test Route (Temporary - for testing)
Route::get('/test-ethereum-connection', function () {
    try {
        $ethereumService = app(\App\Services\EthereumService::class);
        $result = $ethereumService->testConnection();
        
        return response()->json([
            'status' => $result['success'] ? 200 : 500,
            'data' => $result,
            'timestamp' => now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'error' => $e->getMessage(),
            'trace' => config('app.debug') ? $e->getTraceAsString() : null
        ]);
    }
})->name('test.ethereum.connection');


Route::get('/eth-test-env', function () {
    return [
        'rpc' => config('app.env') ? env('ETHEREUM_RPC_URL') : null,
        'address' => env('ETHEREUM_SYSTEM_WALLET_ADDRESS'),
        'pk_len' => strlen((string) env('ETHEREUM_PRIVATE_KEY')), // don't print key
    ];
});

// Smart Contract Test Route (Temporary - for testing)
Route::get('/test-smart-contract', function () {
    try {
        $contractService = app(\App\Services\SmartContractService::class);
        
        if (!$contractService->isInitialized()) {
            return response()->json([
                'status' => 400,
                'error' => 'Contract not initialized',
                'message' => 'Please configure ETHEREUM_CONTRACT_ADDRESS in .env and ensure ABI file exists'
            ], 400);
        }
        
        $info = $contractService->getContractInfo();
        
        return response()->json([
            'status' => 200,
            'data' => $info,
            'timestamp' => now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'error' => $e->getMessage(),
            'trace' => config('app.debug') ? $e->getTraceAsString() : null
        ], 500);
    }
})->name('test.smart.contract');



Route::get('/_migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return nl2br(Artisan::output());
});