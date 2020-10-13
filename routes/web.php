<?php
Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::group(array('namespace' => 'Administrator\v1'),function(){
    Route::get('login', 'UserController@login')->name('user.login');
    Route::get('forgot-password', 'UserController@forgotPassword')->name('user.forgot.password');
    Route::get('verify-otp/{id}', 'UserController@verifyOTP')->name('user.verify.otp');
    Route::get('new-password/{id}', 'UserController@newPassword')->name('user.new.password');

    Route::post('authorized', 'UserController@authorized')->name('user.authorized');
    Route::post('verify-forgot-password', 'UserController@userForgot')->name('user.forgot');
    Route::post('verify-otp', 'UserController@verifyOTPNumber')->name('verify.otp');
    Route::post('register-new-password', 'UserController@registerNewPassword')->name('register.new.password');
});

//Administrator
Route::group(array('namespace' => 'Administrator\v1','middleware' => ['sentinel.auth']),function(){
    Route::get('/', 'DashboardController@index')->name('user.dashboard');
    Route::get('logout', 'UserController@logout')->name('user.logout');

    //company
    Route::get('company', 'CompanyController@company')->name('company');
    Route::get('ajax-company', 'CompanyController@companyListAjax')->name('company.list.ajax');
    Route::get('company-sticker/{id}', 'CompanyController@companySticker')->name('sticker.company');
    Route::post('select-company', 'CompanyController@companySelect')->name('company.select');
    Route::post('register-company', 'CompanyController@registerCompany')->name('company.register');
    Route::post('edit-company', 'CompanyController@editCompany')->name('company.edit');
    Route::post('company-info', 'CompanyController@companyInfo')->name('company.info');


    //reference
    Route::get('reference', 'ReferenceController@referenceList')->name('reference');
    Route::get('ajax-reference', 'ReferenceController@getReferenceList')->name('reference.list.ajax');
    Route::post('register-ref', 'ReferenceController@registerRef')->name('ref.register');
    Route::post('update-ref', 'ReferenceController@updateRef')->name('ref.update');
    Route::post('select-ref', 'ReferenceController@refSelect')->name('ref.select');
    Route::post('ref-info', 'ReferenceController@refInfo')->name('ref.info');

    //other
    Route::post('age-calculator', 'EnrollmentController@ageCalculator')->name('age.calculator');


    //enrollment
    Route::get('enrollment', 'EnrollmentController@enrollment')->name('enrollment');
    Route::get('new-enrollment', 'EnrollmentController@newEnrollment')->name('new.enrollment');
    Route::get('ajax-enrollment', 'EnrollmentController@enrollmentListAjax')->name('enrollment.list.ajax');
    Route::get('download-enrollment/{id}', 'EnrollmentController@enrollmentPdf')->name('enrollment.pdf');
    Route::post('register-enrollment', 'EnrollmentController@enrollmentRegister')->name('enrollment.register');
    Route::get('edit-enrollment/{id}', 'EnrollmentController@editEnrollment')->name('enrollment.edit');
    Route::post('update-enrollment/{id}', 'EnrollmentController@enrollmentUpdate')->name('enrollment.update');
    Route::get('download-certificate/{id}/{cid?}', 'EnrollmentController@enrollmentCertificate')->name('certificate.pdf');
    Route::post('enrollment-info', 'EnrollmentController@enrollmentInfo')->name('enrollment.info');
    Route::get('renew-enrollment/{id}', 'EnrollmentController@renewEnrollment')->name('enrollment.renew');
    Route::get('ajax-expire', 'EnrollmentController@expiredCertificate')->name('expire.list.ajax');
    Route::post('update-expire-status', 'EnrollmentController@updateExpireStatus')->name('update.expire.status');
    Route::get('enrollment-sticker/{id}', 'EnrollmentController@enrollmentSticker')->name('sticker.enrollment');

    //vision
    Route::get('vision', 'CertificateController@vision')->name('vision');
    Route::get('new-vision', 'CertificateController@newVision')->name('new.vision');
    Route::get('download-vision/{id}', 'CertificateController@visionPdf')->name('vision.pdf');
    Route::post('register-vision', 'CertificateController@visionRegister')->name('vision.register');
    Route::get('ajax-vision', 'CertificateController@visionListAjax')->name('vision.list.ajax');
    Route::get('edit-vision/{id}', 'CertificateController@editVision')->name('vision.edit');
    Route::post('update-vision/{id}', 'CertificateController@visionUpdate')->name('vision.update');
    Route::get('renew-vision/{id}', 'CertificateController@renewVision')->name('vision.renew');


    //cource
    Route::get('course', 'CourseController@courceList')->name('course');
    Route::get('ajax-course-list', 'CourseController@getCourseList')->name('course.list.ajax');
    Route::get('info-course', 'CourseController@infoCourse')->name('course.info');
    Route::post('update-course', 'CourseController@updateCourse')->name('course.update');

    //designation
    Route::get('designation', 'DesignationController@designationList')->name('designation');
    Route::get('ajax-designation-list', 'DesignationController@getDesignationList')->name('designation.list.ajax');
    Route::get('info-designation', 'DesignationController@infoDesignation')->name('designation.info');
    Route::post('update-designation', 'DesignationController@updateDesignation')->name('designation.update');
    Route::post('register-designation', 'DesignationController@registerDesignation')->name('designation.register');

    //education
    Route::get('education', 'EducationController@educationList')->name('education');
    Route::get('ajax-education-list', 'EducationController@getEducationList')->name('education.list.ajax');
    Route::get('info-education', 'EducationController@infoEducation')->name('education.info');
    Route::post('update-education', 'EducationController@updateEducation')->name('education.update');
    Route::post('register-education', 'EducationController@registerEducation')->name('education.register');

    //invoice
    Route::get('invoice', 'InvoiceController@invoice')->name('invoice');
    Route::get('new-invoice', 'InvoiceController@newInvoice')->name('new.invoice');
    Route::post('register-invoice', 'InvoiceController@registerInvoice')->name('vision.register');
    Route::get('ajax-invoice-list', 'InvoiceController@getInvoiceList')->name('invoice.list.ajax');

    //profile
    Route::get('profile', 'UserController@profile')->name('profile');
    Route::post('update-profile', 'UserController@updateProfile')->name('update.profile');

    //redirect
    Route::get('redirecting', 'UserController@redirecting')->name('redirecting');
    Route::post('upload-image', 'UserController@uploadImage')->name('upload.image');
});
