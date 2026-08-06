<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Panel Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Panel', 'prefix' => 'panel', 'middleware' => ['impersonate', 'panel', 'share', 'check_maintenance', 'check_restriction']], function () {

    /* Dashboard */
    Route::get('/', 'DashboardController@index');
    Route::post('/dashboard/save-settings', 'DashboardController@saveSettings');
    Route::get('/dashboard/weak-points', 'DashboardController@weakPoints');
    Route::get('/admin-performance', 'DashboardController@adminPerformance');

    /* Events */
    Route::group(['prefix' => 'events'], function () {
        Route::get('/', 'EventsController@index');
        Route::post('/get-by-day', 'EventsController@getEventsByDay');
        Route::post('/live-course/store', 'EventsController@storeLiveCourse');
    });

    Route::post('/content-delete-request', 'ContentDeleteRequestController@store');

    Route::group(['prefix' => 'users'], function () {
        Route::post('/offlineToggle', 'UserController@offlineToggle');
        Route::get('/{id}/getInfo', 'UserController@getUserInfo');


        Route::get('/login-history/{session_id}/end-session', 'UserLoginHistoryController@endSession');
    });

    Route::group(['prefix' => 'courses'], function () {
        Route::group(['middleware' => 'user.not.access'], function () {

            Route::get('/', 'MyCoursesController@index');
            Route::get('/invitations', 'MyCoursesController@invitations');

            Route::get('/new', 'WebinarController@create');
            Route::post('/store', 'WebinarController@store');
            Route::get('/{id}/module-editor', 'WebinarController@moduleEditorView');
            Route::get('/{id}/step/{step?}', 'WebinarController@edit');
            Route::get('/{id}/edit', 'WebinarController@edit')->name('panel_edit_webinar');
            Route::post('/{id}/update', 'WebinarController@update');
            Route::get('/{id}/delete', 'WebinarController@destroy');
            Route::get('/{id}/duplicate', 'WebinarController@duplicate');
            Route::get('/{id}/export-students-list', 'WebinarController@exportStudentsList');
            Route::post('/order-items', 'WebinarController@orderItems');
            Route::post('/{id}/getContentItemByLocale', 'WebinarController@getContentItemByLocale');

            Route::group(['prefix' => '{webinar_id}/statistics'], function () {
                Route::get('/', 'WebinarStatisticController@index');
            });
        });

        Route::get('/organization_classes', 'MyOrganizationCoursesController@index');

        Route::get('/{id}/sale/{sale_id}/invoice', 'WebinarController@invoice');
        Route::get('/{id}/getNextSessionInfo', 'WebinarController@getNextSessionInfo');

        Route::group(['prefix' => 'purchases'], function () {
            Route::get('/', 'MyPurchasedCoursesController@index');
            Route::post('/getJoinInfo', 'MyPurchasedCoursesController@getJoinInfo');
            Route::get('/learning/{slug}', 'MyPurchasedCoursesController@showLearning');
            Route::get('/{slug}', 'MyPurchasedCoursesController@showCourse');
        });

        Route::post('/search', 'WebinarController@search');

        Route::group(['prefix' => 'comments'], function () {
            Route::get('/', 'MyCourseCommentsController@index');
            Route::post('/store', 'MyCourseCommentsController@store');
            Route::post('/{id}/update', 'MyCourseCommentsController@update');
            Route::get('/{id}/delete', 'MyCourseCommentsController@destroy');
            Route::post('/{id}/reply', 'MyCourseCommentsController@reply');
            Route::post('/{id}/report', 'MyCourseCommentsController@report');
        });

        Route::get('/my-comments', 'MyCommentsController@index');

        Route::group(['prefix' => 'favorites'], function () {
            Route::get('/', 'FavoriteController@index');
            Route::get('/{id}/delete', 'FavoriteController@destroy');
        });

        Route::group(['prefix' => 'personal-notes'], function () {
            Route::get('/', 'CoursePersonalNotesController@index');
            Route::get('/{id}/delete', 'CoursePersonalNotesController@delete');
        });
    });

    Route::group(['prefix' => 'upcoming_courses'], function () {
        Route::group(['middleware' => 'user.not.access'], function () {
            Route::get('/', 'UpcomingCoursesController@index');
            Route::get('/new', 'UpcomingCoursesController@create');
            Route::post('/store', 'UpcomingCoursesController@store');
            Route::get('/{id}/step/{step?}', 'UpcomingCoursesController@edit');
            Route::get('/{id}/edit', 'UpcomingCoursesController@edit');
            Route::post('/{id}/update', 'UpcomingCoursesController@update');
            Route::get('/{id}/delete', 'UpcomingCoursesController@destroy');
            Route::post('/order-items', 'UpcomingCoursesController@orderItems');
            Route::post('/{id}/getContentItemByLocale', 'UpcomingCoursesController@getContentItemByLocale');

            Route::get('/{id}/assign-course', 'UpcomingCoursesController@assignCourseModal');
            Route::post('/{id}/assign-course', 'UpcomingCoursesController@storeAssignCourse');
            Route::get('/{id}/followers', 'UpcomingCourseFollowersController@index');
        });

        Route::get('/followings', 'UpcomingCourseFollowingsController@index');
        Route::get('/followings/{upcoming_id}/delete', 'UpcomingCourseFollowingsController@deleteFollowing');
    });

    Route::group(['prefix' => 'quizzes'], function () {
        Route::group(['middleware' => 'user.not.access'], function () {

            Route::get('/', 'QuizController@index');
            Route::get('/new', 'QuizController@create');
            Route::post('/store', 'QuizController@store');
            Route::get('/{id}/edit', 'QuizController@edit')->name('panel_edit_quiz');
            Route::post('/{id}/update', 'QuizController@update');
            Route::get('/{id}/delete', 'QuizController@destroy');
            Route::post('/{id}/order-items', 'QuizController@orderItems');
        });

        Route::get('/{id}/overview', 'QuizController@overview');
        Route::get('/{id}/start', 'QuizController@start');
        Route::post('/{id}/store-result', 'QuizController@quizzesStoreResult');
        Route::get('/{quizResultId}/status', 'QuizController@status')->name('quiz_status');

        Route::get('/opens', 'OpenQuizzesController@index');
        Route::get('/my-results', 'QuizMyResultsController@index');


        Route::group(['prefix' => 'results'], function () {
            Route::get('/', 'QuizResultsController@index');
            Route::get('/{quizResultId}/details', 'QuizResultsController@show');
            Route::get('/{quizResultId}/edit', 'QuizResultsController@edit');
            Route::post('/{quizResultId}/update', 'QuizResultsController@update');
            Route::get('/{quizResultId}/delete', 'QuizResultsController@delete');
            Route::get('/{quizResultId}/showCertificate', 'QuizResultsController@makeCertificate');
        });


    });

    Route::group(['prefix' => 'quizzes-questions'], function () {
        Route::get('/get-form', 'QuizQuestionController@getForm');
        Route::post('/store', 'QuizQuestionController@store');
        Route::get('/{id}/edit', 'QuizQuestionController@edit');
        Route::get('/{id}/getQuestionByLocale', 'QuizQuestionController@getQuestionByLocale');
        Route::post('/{id}/update', 'QuizQuestionController@update');
        Route::get('/{id}/delete', 'QuizQuestionController@destroy');
    });

    Route::group(['prefix' => 'filters'], function () {
        Route::get('/get-by-category-id/{categoryId}', 'FilterController@getByCategoryId');
    });

    Route::group(['prefix' => 'tickets'], function () {
        Route::post('/store', 'TicketController@store');
        Route::post('/{id}/update', 'TicketController@update');
        Route::get('/{id}/delete', 'TicketController@destroy');
    });

    Route::group(['prefix' => 'sessions'], function () {
        Route::post('/store', 'SessionController@store');
        Route::post('/{id}/update', 'SessionController@update');
        Route::get('/{id}/delete', 'SessionController@destroy');
        Route::get('/{id}/joinToBigBlueButton', 'SessionController@joinToBigBlueButton');
        Route::get('/{id}/joinToAgora', 'SessionController@joinToAgora');
        Route::get('/{id}/endAgora', 'SessionController@endAgora');
        Route::get('/{id}/toggleUsersJoinToAgora', 'SessionController@toggleUsersJoinToAgora');
        Route::get('/{id}/joinToJitsi', 'SessionController@joinToJitsi');
    });

    Route::group(['prefix' => 'chapters'], function () {
        Route::get('/get-form', 'ChapterController@getForm');
        Route::get('/{id}', 'ChapterController@getChapter');
        Route::get('/getAllByWebinarId/{webinar_id}', 'ChapterController@getAllByWebinarId');
        Route::post('/store', 'ChapterController@store');
        Route::get('/{id}/edit', 'ChapterController@edit');
        Route::post('/{id}/update', 'ChapterController@update');
        Route::get('/{id}/delete', 'ChapterController@destroy');
        Route::post('/change', 'ChapterController@change');
    });

    Route::group(['prefix' => 'files'], function () {
        Route::post('/store', 'FileController@store');
        Route::post('/{id}/update', 'FileController@update');
        Route::get('/{id}/delete', 'FileController@destroy');
    });

    Route::group(['prefix' => 'assignments'], function () {
        Route::post('/store', 'AssignmentController@store');
        Route::post('/{id}/update', 'AssignmentController@update');
        Route::get('/{id}/delete', 'AssignmentController@destroy');

        Route::get('/my-requests', 'AssignmentController@myAssignments');
        Route::get('/', 'AssignmentController@myCoursesAssignments');
        Route::get('/histories', 'AssignmentController@myCoursesAssignmentsAllHistories');
        Route::get('/{id}/students', 'AssignmentController@students');
    });

    Route::group(['prefix' => 'text-lesson'], function () {
        Route::post('/store', 'TextLessonsController@store');
        Route::post('/{id}/update', 'TextLessonsController@update');
        Route::get('/{id}/delete', 'TextLessonsController@destroy');
    });

    Route::group(['prefix' => 'prerequisites'], function () {
        Route::post('/store', 'PrerequisiteController@store');
        Route::post('/{id}/update', 'PrerequisiteController@update');
        Route::get('/{id}/delete', 'PrerequisiteController@destroy');
    });

    Route::group(['prefix' => 'relatedCourses'], function () {
        Route::post('/store', 'RelatedCoursesController@store');
        Route::post('/{id}/update', 'RelatedCoursesController@update');
        Route::get('/{id}/delete', 'RelatedCoursesController@destroy');
    });

    Route::group(['prefix' => 'relatedProducts'], function () {
        Route::post('/store', 'RelatedProductsController@store');
        Route::post('/{id}/update', 'RelatedProductsController@update');
        Route::get('/{id}/delete', 'RelatedProductsController@destroy');
    });

    Route::group(['prefix' => 'faqs'], function () {
        Route::post('/store', 'FAQController@store');
        Route::post('/{id}/update', 'FAQController@update');
        Route::get('/{id}/delete', 'FAQController@destroy');
    });

    Route::group(['prefix' => 'webinar-extra-description'], function () {
        Route::post('/store', 'WebinarExtraDescriptionController@store');
        Route::post('/{id}/update', 'WebinarExtraDescriptionController@update');
        Route::get('/{id}/delete', 'WebinarExtraDescriptionController@destroy');
    });

    Route::group(['prefix' => 'webinar-quiz'], function () {
        Route::post('/store', 'WebinarQuizController@store');
        Route::post('/{id}/update', 'WebinarQuizController@update');
        Route::get('/{id}/delete', 'WebinarQuizController@destroy');
    });


    Route::group(['prefix' => 'certificates'], function () {
        Route::get('/', 'CertificatesListsController@index');
        Route::get('/students', 'GeneratedCertificatesController@index');
        Route::get('/students/{certificateId}/show', 'GeneratedCertificatesController@download');
        Route::get('/{type}/{typeItemId}/details', 'GeneratedCertificatesController@index')->where('type', 'quiz|courses|bundles');

        /* My */
        Route::group(['prefix' => 'my-achievements'], function () {
            Route::get('/', 'MyCertificatesController@index');
            Route::get('/{certificateId}/show', 'MyCertificatesController@download');
        });
    });

    Route::group(['prefix' => 'meetings'], function () {
        Route::get('/reservation', 'ReserveMeetingController@reservation');
        Route::get('/requests', 'ReserveMeetingController@requests');

        Route::get('/settings', 'MeetingController@setting')->name('meeting_setting');
        Route::get('/get-meeting-time-modal', 'MeetingController@getMeetingTimeModal');
        Route::post('/{id}/update', 'MeetingController@update');
        Route::post('saveTime', 'MeetingController@saveTime');
        Route::post('deleteTime', 'MeetingController@deleteTime');
        Route::post('temporaryDisableMeetings', 'MeetingController@temporaryDisableMeetings');


        Route::get('/{id}/join-modal', 'ReserveMeetingController@getJoinModal');
        Route::get('/{id}/join', 'ReserveMeetingController@join');
        Route::post('/create-link', 'ReserveMeetingController@createLink');
        Route::get('/{id}/get-finish-modal', 'ReserveMeetingController@getFinishModal');
        Route::post('/{id}/finish', 'ReserveMeetingController@finish');

        Route::get('/{id}/create-session', 'ReserveMeetingController@getCreateSessionModal');
        Route::post('/{id}/create-session', 'ReserveMeetingController@createSession');

        Route::get('/{id}/contact-info', 'ReserveMeetingController@getContactInfoModal');
    });

    // Instructor    // Student Tracking
    Route::group(['prefix' => 'students-tracking'], function () {
        Route::get('/', 'StudentTrackingController@index');
        Route::get('/{student_id}/details', 'StudentTrackingController@details');
        Route::get('/{student_id}/courses', 'StudentTrackingController@courses');
        Route::get('/{student_id}/quizResults', 'StudentTrackingController@quizResults');
        Route::get('/{student_id}/assignments', 'StudentTrackingController@assignments');
        Route::post('/{student_id}/sendMessage', 'StudentTrackingController@sendMessage');
        Route::get('/export', 'StudentTrackingController@export');
    });

    // My Students - Teachers only (students enrolled in courses)
    Route::get('/my-students', 'MyStudentsController@dashboard');
    Route::get('/my-students/list', 'MyStudentsController@index');
    Route::get('/my-students/chart-data', 'MyStudentsController@chartData');
    Route::get('/my-students/add-student', 'MyStudentsController@addStudentForm');
    Route::post('/my-students/add-student', 'MyStudentsController@addStudentStore');

    Route::group(['prefix' => 'financial'], function () {
        Route::get('/sales', 'SaleController@index');
        Route::get('/summary', 'AccountingSummaryController@index');

        Route::get('/account', 'AccountingController@account');
        Route::post('/charge', 'AccountingController@charge');

        /* Payout */
        Route::group(['prefix' => 'payout'], function () {
            Route::get('/', 'PayoutController@index');
            Route::post('/request', 'PayoutController@requestPayout');
            Route::get('/{id}/details', 'PayoutController@getDetails');
        });

        Route::group(['prefix' => 'offline-payments'], function () {
            Route::get('/{id}/edit', 'AccountingController@account');
            Route::post('/{id}/update', 'AccountingController@updateOfflinePayment');
            Route::get('/{id}/delete', 'AccountingController@deleteOfflinePayment');
        });

        Route::group(['prefix' => 'subscribes'], function () {
            Route::get('/', 'SubscribesController@index');
            Route::get('/{id}/installments', 'SubscribesController@getInstallmentsBySubscribe');
        });
        Route::post('/pay-subscribes', 'SubscribesController@pay');

        Route::group(['prefix' => 'registration-packages'], function () {
            Route::get('/', 'RegistrationPackagesController@index')->name('panelRegistrationPackagesLists');
            Route::get('/{id}/installments', 'RegistrationPackagesController@getInstallmentsByRegistrationPackage');
            Route::post('/pay-registration-packages', 'RegistrationPackagesController@pay')->name('payRegistrationPackage');
        });

        Route::group(['prefix' => 'installments'], function () {
            Route::get('/', 'InstallmentsController@index');
            Route::get('/{id}/details', 'InstallmentsController@show');
            Route::get('/{id}/cancel', 'InstallmentsController@cancelVerification');
            Route::get('/{id}/pay_upcoming_part', 'InstallmentsController@payUpcomingPart');
            Route::get('/{id}/steps/{step_id}/pay', 'InstallmentsController@payStep');
        });
    });

    Route::group(['prefix' => 'setting'], function () {
        Route::get('/step/{step?}', 'UserController@setting');
        Route::get('/', 'UserController@setting');
        Route::post('/', 'UserController@update');
        Route::post('/metas', 'UserController@storeMetas');
        Route::post('metas/{meta_id}/update', 'UserController@updateMeta');
        Route::get('metas/{meta_id}/delete', 'UserController@deleteMeta');
        Route::get('/deleteAccount', 'UserController@deleteAccount');
        Route::get('/media/{type}/delete', 'UserController@deleteUserMedia');

        // Teacher-profile manager actions
        Route::post('/{id}/kpi',   'UserController@updateTeacherKpi')->name('teacher-profile.kpi');
        Route::post('/{id}/level', 'UserController@updateTeacherLevel')->name('teacher-profile.level');

        Route::group(['prefix' => '/attachments'], function () {
            Route::get('/get-form', 'UserProfileAttachmentsController@getForm');
            Route::post("/store", 'UserProfileAttachmentsController@store');
            Route::get("/{id}/edit", 'UserProfileAttachmentsController@edit');
            Route::post("/{id}/update", 'UserProfileAttachmentsController@update');
            Route::get("/{id}/delete", 'UserProfileAttachmentsController@delete');
        });
    });

    Route::group(['prefix' => 'support'], function () {
        Route::get('/', 'SupportsController@index');
        Route::get('/new', 'SupportsController@create');
        Route::post('/store', 'SupportsController@store');
        Route::get('{id}/conversations', 'SupportsController@index');
        Route::post('{id}/conversations', 'SupportsController@storeConversations');
        Route::get('{id}/close', 'SupportsController@close');
        Route::post('/ajax-create', 'SupportsController@ajaxCreate');
        Route::post('{id}/ajax-reply', 'SupportsController@ajaxReply');

        Route::group(['prefix' => 'tickets'], function () {
            Route::get('/', 'SupportsController@tickets');
            Route::get('{id}/conversations', 'SupportsController@tickets');
        });
    });

    Route::group(['prefix' => 'marketing', 'middleware' => 'user.not.access'], function () {

        /* Special Offers */
        Route::group(['prefix' => 'special_offers'], function () {
            Route::get('/', 'SpecialOfferController@index')->name('special_offer_index');
            Route::post('/store', 'SpecialOfferController@store');
            Route::get('/{id}/disable', 'SpecialOfferController@disable');
        });

        /* Promotions */
        Route::group(['prefix' => 'promotions'], function () {
            Route::get('/', 'PromotionsController@index');
            Route::get('/{id}/pay-form', 'PromotionsController@getPayForm');
            Route::post('/{id}/pay', 'PromotionsController@payPromotion');
        });
    });

    Route::group(['prefix' => 'marketing'], function () {
        Route::get('/affiliates', 'AffiliateController@index');

        /* Registration Bonus */
        Route::get('/registration_bonus', 'RegistrationBonusController@index');

        /* Discounts */
        Route::group(['prefix' => 'discounts'], function () {
            Route::get('/', 'DiscountController@index');
            Route::get('/new', 'DiscountController@create');
            Route::post('/store', 'DiscountController@store');
            Route::get('/{id}/edit', 'DiscountController@edit');
            Route::post('/{id}/update', 'DiscountController@update');
            Route::get('/{id}/delete', 'DiscountController@delete');
        });

    });

    Route::group(['prefix' => 'noticeboard'], function () {
        Route::get('/', 'NoticeboardController@index');
        Route::get('/new', 'NoticeboardController@create');
        Route::post('/store', 'NoticeboardController@store');
        Route::get('/{noticeboard_id}/edit', 'NoticeboardController@edit');
        Route::post('/{noticeboard_id}/update', 'NoticeboardController@update');
        Route::get('/{noticeboard_id}/delete', 'NoticeboardController@delete');
        Route::get('/{noticeboard_id}/saveStatus', 'NoticeboardController@saveStatus');
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationsController@index');
        Route::get('/{id}/saveStatus', 'NotificationsController@saveStatus');
        Route::get('/mark-all-as-read', 'NotificationsController@markAllAsRead');
    });

    // organization instructor and students route
    Route::group(['prefix' => 'manage'], function () {
        Route::get('/{user_type}', 'OrganManageUsersController@manageUsers');
        Route::get('/{user_type}/new', 'OrganManageUsersController@createUser');
        Route::post('/{user_type}/new', 'OrganManageUsersController@storeUser');
        Route::get('/{user_type}/{user_id}/edit', 'OrganManageUsersController@editUser');
        Route::get('/{user_type}/{user_id}/edit/step/{step?}', 'OrganManageUsersController@editUser');
        Route::get('/{user_type}/{user_id}/delete', 'OrganManageUsersController@deleteUser');
    });

    Route::group(['prefix' => 'rewards'], function () {
        Route::get('/', 'RewardController@index');
        Route::post('/exchange', 'RewardController@exchange');
    });

    Route::group(['prefix' => 'store', 'namespace' => 'Store'], function () {
        Route::group(['middleware' => 'user.not.access'], function () {

            Route::group(['prefix' => 'products'], function () {
                Route::get('/', 'ProductController@index');
                Route::get('/new', 'ProductController@create');
                Route::post('/store', 'ProductController@store');
                Route::get('/{id}/step/{step?}', 'ProductController@edit');
                Route::get('/{id}/edit', 'ProductController@edit');
                Route::post('/{id}/update', 'ProductController@update');
                Route::get('/{id}/delete', 'ProductController@destroy');
                Route::get('/{id}/media/{mediaId}/delete', 'ProductController@deleteMediaById');
                Route::post('/{id}/getContentItemByLocale', 'ProductController@getContentItemByLocale');
                Route::post('/search', 'ProductController@search');

                Route::group(['prefix' => 'filters'], function () {
                    Route::get('/get-by-category-id/{categoryId}', 'ProductFilterController@getByCategoryId');
                });

                Route::group(['prefix' => 'specifications'], function () {
                    Route::get('/{id}/get', 'ProductSpecificationController@getItem');
                    Route::post('/store', 'ProductSpecificationController@store');
                    Route::post('/{id}/update', 'ProductSpecificationController@update');
                    Route::get('/{id}/delete', 'ProductSpecificationController@destroy');
                    Route::post('/order-items', 'ProductSpecificationController@orderItems');
                    Route::post('/search', 'ProductSpecificationController@search');
                    Route::get('/get-by-category-id/{categoryId}', 'ProductSpecificationController@getByCategoryId');
                });

                Route::group(['prefix' => 'files'], function () {
                    Route::post('/store', 'ProductFileController@store');
                    Route::post('/{id}/update', 'ProductFileController@update');
                    Route::get('/{id}/delete', 'ProductFileController@destroy');
                    Route::post('/order-items', 'ProductFileController@orderItems');
                });

                Route::group(['prefix' => 'faqs'], function () {
                    Route::post('/store', 'ProductFaqController@store');
                    Route::post('/{id}/update', 'ProductFaqController@update');
                    Route::get('/{id}/delete', 'ProductFaqController@destroy');
                    Route::post('/order-items', 'ProductFaqController@orderItems');
                });

                Route::group(['prefix' => 'comments'], function () {
                    Route::get('/', 'CommentController@index');
                });
            });

            Route::group(['prefix' => 'sales'], function () {
                Route::get('/', 'SaleController@index');
                Route::get('/{id}/productOrder/{order_id}/invoice', 'SaleController@invoice');
                Route::get('/{id}/getProductOrder/{order_id}', 'SaleController@getProductOrder');
                Route::post('/{id}/productOrder/{order_id}/setTrackingCode', 'SaleController@setTrackingCode');
            });
        });

        Route::group(['prefix' => 'purchases'], function () {
            Route::get('/', 'MyPurchaseController@index');
            Route::get('/{id}/getProductOrder/{order_id}', 'MyPurchaseController@getProductOrder');
            Route::get('/{id}/productOrder/{order_id}/setGotTheParcel', 'MyPurchaseController@setGotTheParcel');
            Route::get('/{id}/productOrder/{order_id}/invoice', 'MyPurchaseController@invoice');
        });


        Route::group(['prefix' => 'products'], function () {
            Route::get('/my-comments', 'MyCommentController@index');
            Route::get('/files/{id}/download', 'ProductFileController@download');
            Route::get('/{id}/getFilesModal', 'ProductController@getFilesModal');
        });
    });

    Route::group(['prefix' => 'bundles'], function () {
        Route::group(['middleware' => 'user.not.access'], function () {
            Route::get('/', 'BundlesController@index');
            Route::get('/new', 'BundlesController@create');
            Route::post('/store', 'BundlesController@store');
            Route::get('/{id}/step/{step?}', 'BundlesController@edit');
            Route::get('/{id}/edit', 'BundlesController@edit');
            Route::post('/{id}/update', 'BundlesController@update');
            Route::get('/{id}/delete', 'BundlesController@destroy');
            Route::post('/{id}/getContentItemByLocale', 'BundlesController@getContentItemByLocale');
            Route::get('/{id}/courses', 'BundlesController@courses');
            Route::get('/{id}/modules', 'BundlesController@modules');
            Route::get('/{bundleId}/module/create', 'BundlesController@moduleCreate');
            Route::get('/{bundleId}/module/{courseId}/edit', 'BundlesController@moduleEdit');
            Route::post('/{bundleId}/module/{courseId}/delete', 'BundlesController@moduleDestroy');
            Route::get('/{id}/export-students-list', 'BundlesController@exportStudentsList');
            Route::post('/{id}/duplicate', 'BundlesController@duplicate');
            Route::post('/{id}/toggle-hidden', 'BundlesController@toggleHidden');
        });
    });

    Route::group(['prefix' => 'bundle-webinars'], function () {
        Route::post('/store', 'BundleWebinarsController@store');
        Route::post('/{id}/update', 'BundleWebinarsController@update');
        Route::get('/{id}/delete', 'BundleWebinarsController@destroy');
    });

    Route::group(['prefix' => 'course-noticeboard'], function () {
        Route::get('/', 'CourseNoticeboardController@index');
        Route::get('/new', 'CourseNoticeboardController@create');
        Route::post('/store', 'CourseNoticeboardController@store');
        Route::get('/{noticeboard_id}/edit', 'CourseNoticeboardController@edit');
        Route::post('/{noticeboard_id}/update', 'CourseNoticeboardController@update');
        Route::get('/{noticeboard_id}/delete', 'CourseNoticeboardController@delete');
        Route::get('/{noticeboard_id}/saveStatus', 'CourseNoticeboardController@saveStatus');
    });

    Route::group(['prefix' => 'forums'], function () {
        Route::get('/topics', 'ForumTopicsController@index');
        Route::get('/topics/{id}/removeBookmarks', 'ForumTopicsController@removeBookmarks');
        Route::get('/posts', 'ForumPostsController@index');

        Route::get('/bookmarks', 'ForumsBookmarksController@index');
    });

    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', 'BlogPostsController@index');
        Route::get('/new', 'BlogPostsController@create');
        Route::post('/store', 'BlogPostsController@store');

        Route::get('/posts-by-category', 'BlogPostsController@getPostsByCategory');

        Route::get('/{post_id}/edit', 'BlogPostsController@edit');
        Route::post('/{post_id}/update', 'BlogPostsController@update');
        Route::get('/{post_id}/delete', 'BlogPostsController@delete');

        Route::group(['prefix' => 'comments'], function () {
            Route::get('/', 'BlogCommentsController@index');
        });

        Route::group(['prefix' => '/{post_id}/related-posts'], function () {
            Route::post('/store', 'BlogRelatedPostsController@store');
            Route::post('/{id}/update', 'BlogRelatedPostsController@update');
            Route::get('/{id}/delete', 'BlogRelatedPostsController@destroy');
        });
    });

    Route::group(['prefix' => 'ai-contents'], function () {
        Route::get('/', 'AiContentController@index');
        Route::post('/generate', 'AiContentController@generate');
    });
    
    // IELTS Test Module - Student Routes
    Route::group(['prefix' => 'ielts-tests'], function () {
        Route::get('/', 'IeltsTestController@index')->name('panel.ielts_tests.index');
        Route::get('/mock', 'IeltsTestController@indexMock')->name('panel.ielts_tests.mock');
        Route::get('/diagnostic', 'IeltsTestController@indexDiagnostic')->name('panel.ielts_tests.diagnostic');
        Route::get('/practice', 'IeltsTestController@indexPractice')->name('panel.ielts_tests.practice');
        Route::get('/{id}', 'IeltsTestController@show')->name('panel.ielts_tests.show');
        Route::post('/{id}/start', 'IeltsTestController@startTest')->name('panel.ielts_tests.start');
        Route::get('/attempt/{attemptId}', 'IeltsTestController@takeTest')->name('panel.ielts_tests.take');
        Route::post('/attempt/{attemptId}/save-answer', 'IeltsTestController@saveAnswer')->name('panel.ielts_tests.save_answer');
        Route::post('/attempt/{attemptId}/finish-section', 'IeltsTestController@finishSection')->name('panel.ielts_tests.finish_section');
        Route::post('/attempt/{attemptId}/submit', 'IeltsTestController@submitTest')->name('panel.ielts_tests.submit');
        Route::get('/attempt/{attemptId}/results', 'IeltsTestController@results')->name('panel.ielts_tests.results');
        Route::get('/attempt/{attemptId}/review', 'IeltsTestController@reviewAnswers')->name('panel.ielts_tests.review');
    });

    // IELTS Grading (Teachers/Admins - grade speaking and writing answers)
    Route::group(['prefix' => 'ielts-grading'], function () {
        Route::get('/', 'IeltsGradingController@index')->name('panel.ielts_grading.index');
        Route::get('/graded', 'IeltsGradingController@graded')->name('panel.ielts_grading.graded');
        Route::get('/{attemptId}/grade/{skill?}', 'IeltsGradingController@grade')->name('panel.ielts_grading.grade');
        Route::post('/{attemptId}/submit', 'IeltsGradingController@submitGrade')->name('panel.ielts_grading.submit');
        Route::get('/answer/{answerId}', 'IeltsGradingController@viewAnswer')->name('panel.ielts_grading.view_answer');
        Route::post('/rate', 'IeltsGradingController@submitRating')->name('panel.ielts_grading.rate');
    });

    // IELTS Test Management (Teachers/Admins - create and manage their tests)
    Route::group(['prefix' => 'my-ielts-tests'], function () {
        Route::get('/', 'IeltsTestManageController@index')->name('panel.my_ielts_tests.index');
        
        // New workflow: Choose Type → Mock/Practice forms
        // Create test from Question Bank
        Route::get('/create', 'IeltsTestInlineController@chooseMethod')->name('panel.my_ielts_tests.create');

        // Choose creation method (new or legacy)
        Route::get('/create-choose-method', 'IeltsTestInlineController@chooseMethod')->name('panel.my_ielts_tests.choose_method');

        // OLD FLOW: Create test from Question Bank (kept for backwards compatibility)
        Route::get('/create-from-bank', 'IeltsTestInlineController@createFromBank')->name('panel.my_ielts_tests.create_from_bank_form');
        Route::post('/store-from-bank', 'IeltsTestManageController@storeFromBank')->name('panel.my_ielts_tests.store_from_bank');

        // NEW FLOW: Create complete test with inline questions
        Route::get('/create-inline', 'IeltsTestInlineController@createInlineComplete')->name('panel.my_ielts_tests.create_inline');
        Route::get('/autosave-inline', 'IeltsTestInlineController@getAutosaveDraft')->name('panel.my_ielts_tests.autosave_inline.get');
        Route::post('/autosave-inline', 'IeltsTestInlineController@saveAutosaveDraft')->name('panel.my_ielts_tests.autosave_inline.save');
        Route::post('/richtext-image-upload', 'IeltsTestInlineController@uploadRichTextImage')->name('panel.my_ielts_tests.richtext_image_upload');
        
        Route::post('/store-inline-complete', 'IeltsTestInlineController@storeInlineComplete')->name('panel.my_ielts_tests.store_inline_complete');
        Route::post('/store-with-groups', 'IeltsTestInlineController@storeWithQuestionGroups')->name('panel.my_ielts_tests.store_with_groups');
        // Route::get('/{id}/preview-student', 'IeltsTestInlineController@previewAsStudent')->name('panel.my_ielts_tests.preview_student');
        Route::get('/{id}/preview-student', 'IeltsTestInlineController@previewInline')->name('panel.my_ielts_tests.preview_student');
        Route::get('/{id}/exit-preview', 'IeltsTestInlineController@exitPreview')->name('panel.my_ielts_tests.exit_preview');
        Route::get('/{id}/edit-inline', 'IeltsTestInlineController@editInlineComplete')->name('panel.my_ielts_tests.edit_inline');
        Route::post('/{id}/update-inline-complete', 'IeltsTestInlineController@updateInlineComplete')->name('panel.my_ielts_tests.update_inline_complete');

        // Legacy routes removed - use create from Question Bank only
        Route::post('/store', 'IeltsTestManageController@store')->name('panel.my_ielts_tests.store');
        Route::get('/{id}/edit', 'IeltsTestManageController@edit')->name('panel.my_ielts_tests.edit');
        Route::post('/{id}/update', 'IeltsTestManageController@update')->name('panel.my_ielts_tests.update');
        Route::get('/{id}/delete', 'IeltsTestManageController@destroy')->name('panel.my_ielts_tests.delete');
        Route::get('/{id}/duplicate', 'IeltsTestManageController@duplicate')->name('panel.my_ielts_tests.duplicate');
        
        // Sections
        Route::get('/{id}/sections', 'IeltsTestManageController@sections')->name('panel.my_ielts_tests.sections');
        Route::get('/{id}/sections/create', 'IeltsTestManageController@createSection')->name('panel.my_ielts_tests.sections.create');
        Route::post('/{id}/sections/store', 'IeltsTestManageController@storeSection')->name('panel.my_ielts_tests.sections.store');
        Route::get('/{id}/sections/{sectionId}/edit', 'IeltsTestManageController@editSection')->name('panel.my_ielts_tests.sections.edit');
        Route::post('/{id}/sections/{sectionId}/update', 'IeltsTestManageController@updateSection')->name('panel.my_ielts_tests.sections.update');
        Route::get('/{id}/sections/{sectionId}/delete', 'IeltsTestManageController@deleteSection')->name('panel.my_ielts_tests.sections.delete');
        
        // Questions
        Route::get('/{id}/sections/{sectionId}/questions', 'IeltsTestManageController@questions')->name('panel.my_ielts_tests.questions');
        Route::get('/{id}/sections/{sectionId}/questions/create', 'IeltsTestManageController@createQuestion')->name('panel.my_ielts_tests.questions.create');
        Route::post('/{id}/sections/{sectionId}/questions/store', 'IeltsTestManageController@storeQuestion')->name('panel.my_ielts_tests.questions.store');
        Route::get('/{id}/sections/{sectionId}/questions/{questionId}/edit', 'IeltsTestManageController@editQuestion')->name('panel.my_ielts_tests.questions.edit');
        Route::post('/{id}/sections/{sectionId}/questions/{questionId}/update', 'IeltsTestManageController@updateQuestion')->name('panel.my_ielts_tests.questions.update');
        Route::get('/{id}/sections/{sectionId}/questions/{questionId}/delete', 'IeltsTestManageController@deleteQuestion')->name('panel.my_ielts_tests.questions.delete');
        
        Route::get('/{id}/submit-approval', 'IeltsTestManageController@submitForApproval')->name('panel.my_ielts_tests.submit_approval');
    });
    
    // Question Bank Management
    Route::group(['prefix' => 'question-bank'], function () {
        // Dashboard
        Route::get('/', 'QuestionBankController@index')->name('panel.question_bank');
        
        // Mock Bank
        Route::get('/mock', 'QuestionBankController@mockList')->name('panel.question_bank.mock.list');
        
        // Practice Bank
        Route::get('/practice', 'QuestionBankController@practiceList')->name('panel.question_bank.practice.list');
        
        // Create (works for both mock and practice)
        Route::get('/create', 'QuestionBankController@create')->name('panel.question_bank.create');
        Route::post('/store', 'QuestionBankController@store')->name('panel.question_bank.store');
        
        // Edit/Update/Delete (same routes for mock and practice)
        Route::get('/{bankType}/{id}/edit', 'QuestionBankController@edit')->name('panel.question_bank.edit');
        Route::post('/{bankType}/{id}/update', 'QuestionBankController@update')->name('panel.question_bank.update');
        Route::get('/{bankType}/{id}/delete', 'QuestionBankController@destroy')->name('panel.question_bank.delete');
        
        //  QUESTION GROUPS - Redirect to new module
        Route::get('/{bankType}/groups', function ($bankType) {
            return redirect()->route('panel.question-groups.index', ['type' => $bankType]);
        })->name('panel.question_bank.groups');
        Route::get('/{bankType}/groups/create', function ($bankType) {
            return redirect()->route('panel.question-groups.create', ['type' => $bankType]);
        })->name('panel.question_bank.groups.create');
        
        //  EXCEL IMPORT (ZIP with Media Files + Preview) 
        Route::get('/import', function () {
            // Redirect to import form with default skill (or show selection page)
            return view('design_1.panel.question_bank.import_select_skill');
        })->name('panel.question_bank.import.index');
        Route::get('/import/{skill}', 'QuestionBankController@importForm')->name('panel.question_bank.import');
        Route::post('/import/preview', 'QuestionBankController@previewImport')->name('panel.question_bank.import.preview');
        Route::post('/import/confirm', 'QuestionBankController@processImport')->name('panel.question_bank.import.confirm');
        Route::post('/import/cancel', 'QuestionBankController@cancelImport')->name('panel.question_bank.import.cancel');
        Route::get('/template/{skill}', 'QuestionBankController@downloadTemplate')->name('panel.question_bank.import.template');
    });

    Route::group(['prefix' => 'question-groups'], function () {
        // list all groups (filter by type=mock or type=practice)
        Route::get('/', 'QuestionGroupController@index')->name('panel.question-groups.index');
        
        // create new group
        Route::get('/create', 'QuestionGroupController@create')->name('panel.question-groups.create');
        Route::post('/', 'QuestionGroupController@store')->name('panel.question-groups.store');
        
        // view group with questions
        Route::get('/{id}', 'QuestionGroupController@show')->name('panel.question-groups.show');
        
        // edit group
        Route::get('/{id}/edit', 'QuestionGroupController@edit')->name('panel.question-groups.edit');
        Route::put('/{id}', 'QuestionGroupController@update')->name('panel.question-groups.update');
        Route::post('/{id}', 'QuestionGroupController@update')->name('panel.question-groups.update-post'); // fallback for forms without @method('PUT')
        
        // Submit for approval
        Route::post('/{id}/submit-approval', 'QuestionGroupController@submitApproval')->name('panel.question-groups.submit-approval');
        
        // delete group
        Route::delete('/{id}', 'QuestionGroupController@destroy')->name('panel.question-groups.destroy');
    });

    Route::group(['prefix' => 'questions'], function () {
        // create question for a group
        Route::get('/groups/{groupId}/create', 'QuestionController@create')->name('panel.questions.create');
        
        // store new question
        Route::post('/groups/{groupId}', 'QuestionController@store')->name('panel.questions.store');
        
        // edit question
        Route::get('/{questionId}/edit', 'QuestionController@edit')->name('panel.questions.edit');
        
        // update question
        Route::put('/{questionId}', 'QuestionController@update')->name('panel.questions.update');
        
        // delete question
        Route::delete('/{questionId}', 'QuestionController@destroy')->name('panel.questions.destroy');
        
        // get type-specific form HTML
        Route::get('/type-form/{type}', 'QuestionController@getQuestionTypeForm')->name('panel.questions.type_form');
    });
    // Dictionary & Flashcard Routes
    Route::get('/vocab-coming-soon', function () {
        return view('design_1.panel.vocab_coming_soon');
    });
    Route::group(['prefix' => 'dictionary'], function () {
        Route::get('/', 'DictionaryController@index');
        Route::get('/dictionaries', 'DictionaryController@getDictionaries');
        Route::get('/search', 'DictionaryController@search');
        Route::post('/search-first', 'DictionaryController@searchFirst');
        Route::get('/did-you-mean', 'DictionaryController@didYouMean');
        Route::get('/nearby-entries', 'DictionaryController@getNearbyEntries');
        Route::get('/entry', 'DictionaryController@getEntry');
        
        // Flashcard Management
        Route::get('/flashcards-preview', 'DictionaryController@flashcardsPreview');
        Route::get('/flashcards', 'DictionaryController@flashcards');
        Route::post('/flashcards/save', 'DictionaryController@saveFlashcard');
        Route::post('/save-flashcard', 'DictionaryController@saveFlashcard');
        Route::delete('/flashcards/{id}', 'DictionaryController@deleteFlashcard');

        // Word Lists Management
        Route::get('/word-lists', 'DictionaryController@wordLists');
        Route::post('/word-lists/create', 'DictionaryController@createWordList');
        Route::get('/word-lists/{id}', 'DictionaryController@viewWordList');
        Route::put('/word-lists/{id}', 'DictionaryController@updateWordList');
        Route::delete('/word-lists/{id}', 'DictionaryController@deleteWordList');
        Route::post('/word-lists/add-word', 'DictionaryController@addWordToList');
        Route::post('/word-lists/remove-word', 'DictionaryController@removeWordFromList');
        Route::post('/my-word-list/add-word', 'DictionaryController@addWordToMyList');
        Route::post('/my-word-list/bulk-delete', 'DictionaryController@bulkDeleteFromMyWordList');
        Route::get('/word-lists-dropdown', 'DictionaryController@getUserWordLists');

        // Academic Word Lists (Band-based)
        Route::get('/academic-word-lists/{id}', 'DictionaryController@getAcademicWordList');
        Route::get('/bundle-word-lists/{id}', 'DictionaryController@getBundleWordList');
        Route::post('/academic-word-lists/mark-learned', 'DictionaryController@markWordAsLearned');
        
        // Practice Mode
        Route::post('/practice/start', 'DictionaryController@startPractice');
        Route::post('/practice/start-bundle-word-list', 'DictionaryController@startBundleWordListPractice');
        Route::post('/practice/start-my-word-list', 'DictionaryController@startMyWordListPractice');
        Route::post('/practice/submit-answer', 'DictionaryController@submitPracticeAnswer');
        
        // My Word List
        Route::get('/my-word-list', 'DictionaryController@getMyWordList');

        // Bundle Vocabulary Library
        Route::get('/bundle-vocabulary/manage', 'DictionaryController@bundleVocabularyManage');
        Route::post('/bundle-vocabulary/store', 'DictionaryController@storeBundleVocabularySet');
        Route::get('/bundle-vocabulary/{id}', 'DictionaryController@showBundleVocabularySet');
        Route::post('/bundle-vocabulary/{id}/update', 'DictionaryController@updateBundleVocabularySet');
        Route::post('/bundle-vocabulary/{id}/submit', 'DictionaryController@submitBundleVocabularySet');
        Route::post('/bundle-vocabulary/{id}/approve', 'DictionaryController@approveBundleVocabularySet');
        Route::post('/bundle-vocabulary/{id}/reject', 'DictionaryController@rejectBundleVocabularySet');
    });

});
