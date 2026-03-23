<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[AuthController::class,'loginPage'])->name('login');
Route::post('/login',[AuthController::class,'checkLogin'])->name('login.submit');

Route::get('/user',[AuthController::class,'userPage'])->name('user');
Route::get('/home',[AuthController::class,'homePage'])->name('home');
Route::get('/listassessor',[AuthController::class,'listassessorPage'])->name('listassessor');
Route::get('/assessor',[AuthController::class,'assessorPage'])->name('assessor');
Route::get('/editassessor/{id}',[AuthController::class,'editassessorPage'])->name('editassessor');
Route::post('/updateassessor/{id}', [AuthController::class, 'updateassessor'])->name('updateassessor');
Route::get('/faculty',[AuthController::class,'facultyPage'])->name('faculty');
Route::get('/university',[AuthController::class,'universityPage'])->name('university');
Route::get('/listname',[AuthController::class,'listnamePage'])->name('listname');
Route::get('/record',[AuthController::class,'recordPage'])->name('record');
Route::get('/results',[AuthController::class,'resultsPage'])->name('results');

Route::get('/report',[AuthController::class,'reportPage'])->name('report');
Route::get('/report/export', [AuthController::class, 'exportExcel'])->name('report.export');
Route::get('/report/export-pdf',[AuthController::class,'exportPDF'])->name('report.export.pdf');
Route::get('/report2/export-excel',[AuthController::class,'exportExcelReport2'])->name('report2.export.excel');
Route::get('/report2/export-pdf',[AuthController::class,'exportPDFReport2'])->name('report2.export.pdf');
Route::get('/report3/export-excel',[AuthController::class,'exportExcelReport3'])->name('report3.export.excel');
Route::get('/report3/export-pdf',[AuthController::class,'exportPDFReport3'])->name('report3.export.pdf');
Route::get('/report4/export-excel',[AuthController::class,'exportExcelReport4'])->name('report4.export.excel');
Route::get('/report4/export-pdf',[AuthController::class,'exportPDFReport4'])->name('report4.export.pdf');
Route::get('/report5/export-excel',[AuthController::class,'exportExcelReport5'])->name('report5.export.excel');
Route::get('/report5/export-pdf',[AuthController::class,'exportPDFReport5'])->name('report5.export.pdf');
Route::get('/report6/export-excel',[AuthController::class,'exportExcelReport6'])->name('report6.export.excel');
Route::get('/report6/export-pdf',[AuthController::class,'exportPDFReport6'])->name('report6.export.pdf');

Route::get('/courseReport',[AuthController::class,'coursereportPage'])->name('coursereport');

Route::get('/listfaculty',[AuthController::class,'listfacultyPage'])->name('listfaculty');
Route::get('/listcourse',[AuthController::class,'listcoursePage'])->name('listcourse');

Route::post('/import-faculty', [AuthController::class, 'importFaculty'])->name('import.faculty');

Route::get('/edituser/{id}',[AuthController::class,'edit'])->name('edituser');
Route::post('/update-user/{id}', [AuthController::class, 'update'])->name('updateuser');

Route::post('/import-users', [AuthController::class, 'import'])->name('import.users');

Route::get('/userfill',[AuthController::class,'userfillPage'])->name('userfill');
Route::post('/userfill', [AuthController::class,'store'])->name('userfill.submit');

Route::get('/save',[AuthController::class,'savePage'])->name('save');
Route::get('/results-collect',[AuthController::class,'collectFaculty'])->name('results.collect');
Route::post('/save-collect', [AuthController::class, 'collect'])->name('save.collect');
Route::get('/editcourse/{faculty}',[AuthController::class,'editcoursePage'])->name('editcourse');

Route::get('/manageassessor',[AuthController::class,'manageassessorPage'])->name('manage-assessor');
Route::post('/course-assessor/store',[AuthController::class,'storemanageassessor'])->name('course-assessor.store');

Route::get('/users/template', [AuthController::class, 'templateUser'])->name('users.template');
Route::post('/import-assessor', [AuthController::class, 'importassessor'])->name('import.assessor');
Route::get('/assessor/template', [AuthController::class, 'templateAssessor'])->name('assessor.template');