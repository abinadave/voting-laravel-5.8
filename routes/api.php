<?php
use Illuminate\Http\Request;

$allowed_domains = [
    'http://personnel-tracking-system.net', 
    'http://app.region8.dilg.gov.ph', 
    'http://localhost:8080',
    'http://voting.region8personnel.com'
];
if(isset($_SERVER['HTTP_ORIGIN'])){
    if (in_array($_SERVER['HTTP_ORIGIN'], $allowed_domains)) {
        // header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    }
}

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: http://personnel-tracking-system.net');
// header('Access-Control-Allow-Origin: http://app.region8.dilg.gov.ph');
// header('Access-Control-Allow-Origin: http://localhost:8080');

header('Access-Control-Allow-Headers: origin, x-requested-with, content-type, authorization, x-csrf-token');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/test', function(){
    return response()->json(['success' => true]);
});

Route::middleware('auth:api')->get('/user', 'UserController@userToken');
Route::middleware('auth:api')->post('/user', 'UserController@userToken');

Route::post('/login', 'UserController@manualLogin');
Route::get('/public/job/paginate', 'JobController@paginate');
Route::post('/public/job/apply_now', 'ApplicantController@applyNow');
Route::post('/public/store_file', 'FileResumeController@fileStore');
Route::post('/public/job/who_applied', 'ApplicantController@fetchByJobId');
Route::post('/public/user_registration', 'UserController@publicRegistrationUser');
Route::post('/public/job/email_duplication', 'ApplicantController@checkDuplicateApplication');
Route::post('/user/check_roles', 'RoleController@checkMyRoles');

//personnel-tracking-system.net Calls
Route::post('/public/get_token', 'UserController@getToken');
Route::post('/public/time_table', 'TimeTableController@corsGetTimeTableByEmployIds');

Route::group(['middleware' => ['auth:api']], function () {

    Route::post('/election/transfer_election_voters_to_other_election', 'VoterController@transferVotersToOtherElections');
    Route::post('/election/transfer_election_nominees_to_other_election', 'NomineeController@transferNomineesToOtherElections');
    Route::post('/nomineevideolink/fetch_video_link_per_election', 'NomineeVideoLinkController@fetchVideoLinksPerElection');
    Route::post('/nomineevideolink/add_video_link', 'NomineeVideoLinkController@insertVideoLink');
    Route::post('/nominee/fetch_all_force', 'NomineeController@fetchNomineesForceAll');
    Route::post('/voter/fetch_voters_who_did_not_vote', 'VoterController@fetchVotersWhoDidNotVote');
    Route::post('/ballot/fetch_who_voted_none', 'BallotController@fetchVotersWhoVotedNone');
    Route::post('/ballot/i_dont_want_to_vote_anyone', 'BallotController@voteNone');
    Route::post('/user/check_hr_roles', 'RoleController@checkUserIfHasHrAccessRoles');
    Route::post('/ballots/fetch_all_ballots_per_election', 'BallotController@fetchAllBallotsPerElection');
    Route::post('/election/update_election', 'ElectionController@updateElection');
    Route::post('/election/reset_election', 'ElectionController@resetElection');
    Route::post('/user/remove_user', 'UserController@removeUser');
    Route::post('/nominee/fetch_nominee_report_views', 'NomineeController@fetchNomineeReportView');
    Route::post('/election/count_participation', 'ElectionController@countElectionParticipation');
    Route::post('/ballot/submit_ballot_vote', 'BallotController@submitBallotVote');
    Route::post('/nominee/search_not_paginate', 'NomineeController@searchNomineesInElection');
    Route::post('/cache/set', 'GlobalController@setCache');
    Route::post('/nominee/paginate', 'NomineeController@paginate');
    Route::post('/election/get_user_related_elections', 'ElectionController@fetchUserRelatedElection');
    Route::post('/election/launch_election', 'ElectionController@launchElection');
    Route::post('/nominee/search_nominee_per_election', 'NomineeController@searchNomineePerElection');
    Route::post('/nominee/count_all_nominees_per_election', 'NomineeController@countAllNomineesPerElectionId');
    Route::post('/nominee/import_all_users', 'NomineeController@importAllUsers');
    Route::post('/nominee/remove_imported_nominee_user', 'NomineeController@removeOneImportedUser');
    Route::post('/nominee/fetch_already_imported_user_id_inside_nominee', 'NomineeController@fetchAlreadyImportedUserIds');
    Route::post('/users/search_users_paginate', 'UserController@searchPaginate');
    Route::post('/nominee/fetch_all_nominee_per_election', 'NomineeController@fetchNomineesByElectionId');
    Route::post('/nominee/import_one_user_as_nominee', 'NomineeController@importOneUser');
    Route::post('/voter/remove_one_voter_from_election', 'VoterController@deleteOneVoterPerElection');
    Route::post('/voter/get_ids_by_election_id', 'VoterController@getVoterIdsPerElection');
    Route::post('/user/import_as_voter', 'VoterController@importOneUserAsVoter');
    Route::post('/voters/import_all_users_to_vote', 'VoterController@importAllUsersToElection');
    Route::get('/users/paginateUsers', 'UserController@paginateUsers');
    Route::post('/voters/search_voter', 'VoterController@searchPerElection');
    Route::post('/voters/count_voters_by_elec_id', 'VoterController@countVotersPerElection');
    Route::post('/voters/generate_voters_key_id', 'VoterController@generateRandomKeyAndId');
    Route::post('/voters/fetch_by_elec_id', 'VoterController@fetchVotersByElectionId');
    Route::post('/voter/add_voter', 'VoterController@insert');
    Route::post('/election/validate_if_elec_id_exist', 'ElectionController@checkIfIdExist');
    Route::post('/election/fetch/election_status_views', 'ElectionStatusController@fetchAll');
    Route::post('/election/delete', 'ElectionController@delete');
    Route::get('/election/fetch', 'ElectionController@fetchAll');
    Route::get('/logout/{id}', 'UserController@logoutManual');

    Route::post('/add/workstation', 'WorkStationController@insert');
    Route::get('/fetch/workstation', 'WorkStationController@fetchAll');
    Route::post('/delete_workstation', 'WorkStationController@delete');
    Route::post('/addjob', 'JobController@addJob');
    Route::post('/add/jobcat', 'JobCategoryController@insert');
    Route::get('/fetchjobcat', 'JobCategoryController@fetch');
    Route::post('/deletejobcat', 'JobCategoryController@deleteOne');
    Route::post('/add/welcomelevel', 'WelcomeLevelController@insert');
    Route::get('/fetch/welcomelevel', 'WelcomeLevelController@fetch');
    Route::post('/delete/welcomelevel', 'WelcomeLevelController@delete');
    Route::post('/add/job/welcomelevel', 'JobWelcomeLevelController@insert');
    Route::get('/job', 'JobController@fetch');
    Route::post('/update/job', 'JobController@updateJob');
    Route::post('/job/welcomelevel/relationship', 'JobWelcomeLevelController@fetchByJobId');
    Route::post('/add/job/shifting', 'JobShiftController@insert');
    Route::post('/job/shifting/relationship', 'JobShiftController@fetchByJobId');
    Route::post('/global_var/column', 'GlobalController@fetchOneRow');
    Route::post('/job_description', 'JobDescriptionController@insert');
    Route::post('/job_description/relationship', 'JobDescriptionController@fetchByJobId');
    Route::post('/job/paginate', 'JobController@paginate');
    Route::post('/download/resume', 'FileResumeController@downloadFile');
    Route::get('/job/search/{keyword}', 'JobController@searchJob');

    Route::post('/clock/add_or_edit_timetable', 'TimeTableController@insertOrEdit');
    Route::get('/clock/current_time', 'TimeTableController@getCurrentDateAndTime');
    Route::post('/clock/employee/current_time_table', 'TimeTableController@getCurrentTimeTableOfEmployee');
    Route::post('/clock/time_table/user_id', 'TimeTableController@fetchByUserId');
    Route::get('/user_admin/users', 'UserController@fetchUsers');
    Route::get('/user_admin/block_user/{id}', 'UserController@blockUserById');
    Route::get('/user_admin/unblock_user/{id}', 'UserController@unBlockUserById');
    Route::get('/fetch/applicants_view', 'ApplicantController@fetchApplicantsView');
    Route::post('/search/applicants_view', 'ApplicantController@search');
    Route::post('/election/create_election', 'ElectionController@createElection');
});


