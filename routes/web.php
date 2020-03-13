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

//you can also set prefix adding by 'prefix' => 'administrator/v1' in route group.

//Administrator
Route::group(array('namespace' => 'Administrator\v1','middleware' => ['sentinel.auth']),function(){
  Route::get('/', 'DashboardController@index')->name('user.dashboard');
  Route::get('logout', 'UserController@logout')->name('user.logout');


  //profile
  Route::get('profile', 'UserController@profile')->name('profile');
  Route::post('update-profile', 'UserController@updateProfile')->name('update.profile');

  //redirect
  Route::get('redirecting', 'UserController@redirecting')->name('redirecting');
  Route::post('upload-image', 'UserController@uploadImage')->name('upload.image');

  //remove all data
  Route::get('remove3635', 'UserController@remove3635')->name('remove.all.profile.data');

  //outstending
  Route::get('outstanding', 'DashboardController@outstanding')->name('outstanding');
  Route::get('outstanding-ajax', 'DashboardController@outstandingAjax')->name('outstanding.ajax');

  //report
  Route::get('day-book', 'DashboardController@dayBook')->name('daybook');
  Route::get('daybook-ajax', 'DashboardController@dayBookAjax')->name('daybook.ajax');

  //profit loss report
  Route::get('profit-loss-report', 'DashboardController@profitloss')->name('profit.loss.report');
  Route::get('profit-loss-report-ajax', 'DashboardController@getProfitLossReport')->name('profit.loss.report.ajax');

  //locaition
  Route::get('state-list', 'LocationController@stateList')->name('states');
  Route::post('register-state-list', 'LocationController@registerState')->name('register.states');
  Route::get('info-states', 'LocationController@infoState')->name('states.info');
  Route::get('ajax-state-list', 'LocationController@getStateList')->name('state.list.ajax');
  Route::post('update-state', 'LocationController@updateState')->name('state.update');
  Route::get('remove-state/{id}', 'LocationController@removeState')->name('state.remove');
  Route::get('city-list', 'LocationController@cityList')->name('cities');
  Route::post('register-city', 'LocationController@registerCities')->name('register.cities');
  Route::get('ajax-city-list', 'LocationController@getCityList')->name('city.list.ajax');
  Route::get('info-city', 'LocationController@infoCities')->name('cities.info');
  Route::post('update-city', 'LocationController@updateCity')->name('city.update');
  Route::get('remove-city/{id}', 'LocationController@removeCity')->name('city.remove');

  //Manufacturer types
  Route::get('types-of-manufacturer', 'ManufacturerController@mtypes')->name('manufacturer.types');
  Route::get('get-types-of-manufacturer', 'ManufacturerController@getMtype')->name('manufacturer.types.get');
  Route::get('info-types-of-manufacturer', 'ManufacturerController@infoMtype')->name('manufacturer.types.info');
  Route::get('remove-types-of-manufacturer/{id}', 'ManufacturerController@removeMtype')->name('manufacturer.types.remove');
  Route::post('activation-types-of-manufacturer', 'ManufacturerController@ActivationMtype')->name('manufacturer.types.activation');
  Route::post('update-types-of-manufacturer', 'ManufacturerController@updateMtype')->name('manufacturer.types.update');
  Route::post('register-manufacturer-types', 'ManufacturerController@registerTypes')->name('manufacturer.types.register');

  //Stock
  Route::get('add-stock/{id}', 'StockController@addStock')->name('stock.new');
  Route::get('stock-settlement/{id}/{uid}', 'StockController@settlementStock')->name('stock.settlement');
  Route::post('register-stock/{id}', 'StockController@registerStock')->name('stock.register');
  Route::post('register-settlement/{id}/{uid}', 'StockController@registerSettlement')->name('settlement.register');
  Route::post('register-stock-quantity/{id}', 'StockController@registerStockQuantity')->name('stock.quantity.register');
  Route::get('ajax-stock-product', 'StockController@getStockProducts')->name('stock.product.ajax');
  Route::get('ajax-stock-return', 'StockController@getStockReturn')->name('stock.return.ajax');
  Route::post('ajax-stock-list/{id}', 'StockController@getStockList')->name('stock.list.ajax');
  Route::post('remove-stock-product/{id}', 'StockController@removeStockProduct')->name('stock.product.remove');
  Route::get('edit-stock/{id}/{uid}', 'StockController@editStock')->name('stock.edit');
  Route::get('remove-stock/{id}/{uid}', 'StockController@removeStock')->name('stock.remove');
  Route::post('update-stock/{id}/{uid}', 'StockController@updateStock')->name('stock.update');
  Route::post('stock-item-info/{id}', 'StockController@getStockItemInfo')->name('stock.item.info');
  Route::post('update-stock-item-quantity', 'StockController@updateStockQuantity')->name('stock.quantity.update');
  Route::post('stock-report', 'StockController@getStockReport')->name('stock.report.ajax');
  Route::get('stock-info', 'StockController@getStockInfo')->name('find.stock');


  //ready stock
  Route::get('ready-stock', 'StockController@readyStock')->name('ready.stock');
  Route::post('register-ready-stock', 'StockController@registerReadyStock')->name('ready.stock.register');

  //user list
  Route::get('user-list', 'UserController@userList')->name('user.list');
  Route::get('user-list-ajax', 'UserController@userListAjax')->name('user.list.ajax');
  Route::post('activation-user', 'UserController@ActivationUser')->name('user.activation');
  Route::get('add-user', 'UserController@userNew')->name('user.new');
  Route::get('edit-user/{id}', 'UserController@editUser')->name('user.edit');
  Route::post('update-user/{id}', 'UserController@updateUser')->name('user.update');
  Route::post('register-user', 'UserController@registerUser')->name('user.register');

  //stok unit
  Route::get('stock-unit', 'StockController@mtypes')->name('stock.unit');
  Route::post('register-stock-types', 'StockController@registerTypes')->name('stock.types.register');
  Route::get('get-types-of-stock', 'StockController@getMtype')->name('stock.types.get');
  Route::get('remove-types-of-stock/{id}', 'StockController@removeMtype')->name('stock.types.remove');
  Route::get('info-types-of-stock', 'StockController@infoMtype')->name('stock.types.info');
  Route::post('update-types-of-stock', 'StockController@updateMtype')->name('stock.types.update');

  //Party
  Route::get('party', 'PartyController@party')->name('party');
  Route::get('ajax-party', 'PartyController@getParty')->name('party.list.ajax');
  Route::get('json-party', 'PartyController@getPartyJson')->name('party.list.json');
  Route::get('add-party', 'PartyController@partyNew')->name('party.new');
  Route::get('party-detail/{id}', 'PartyController@partyView')->name('party.view');
  Route::post('city-list-ajax', 'PartyController@cityListAjax')->name('city.ajax');
  Route::post('register-party', 'PartyController@registerParty')->name('party.register');
  Route::get('edit-party/{id}', 'PartyController@editParty')->name('party.edit');
  Route::post('update-party/{id}', 'PartyController@updateParty')->name('party.update');
  Route::post('activation-party', 'PartyController@ActivationParty')->name('party.activation');
  Route::get('remove-party/{id}', 'PartyController@removeParty')->name('party.remove');
  Route::post('party-transection/{id}', 'PartyController@partyTransection')->name('party.transection');

  //agent
  Route::get('agent', 'AgentController@agent')->name('agent');
  Route::get('ajax-agent', 'AgentController@getAgent')->name('agent.list.ajax');
  Route::get('json-agent', 'AgentController@getAgentJson')->name('agent.list.json');
  Route::get('add-agent', 'AgentController@agentNew')->name('agent.new');
  Route::post('register-agent', 'AgentController@registerAgent')->name('agent.register');
  Route::get('edit-agent/{id}', 'AgentController@editAgent')->name('agent.edit');
  Route::post('update-agent/{id}', 'AgentController@updateAgent')->name('agent.update');
  Route::post('activation-agent', 'AgentController@ActivationAgent')->name('agent.activation');
  Route::get('remove-agent/{id}', 'AgentController@removeAgent')->name('agent.remove');
  Route::get('agent-detail/{id}', 'AgentController@agentView')->name('agent.view');
  Route::post('agent-transection/{id}', 'AgentController@agentTransection')->name('agent.transection');

  //staff
  Route::get('staff', 'StaffController@staff')->name('staff');
  Route::get('ajax-staff', 'StaffController@getStaff')->name('staff.list.ajax');
  Route::get('json-staff', 'StaffController@getStaffJson')->name('staff.list.json');
  Route::get('add-staff', 'StaffController@staffNew')->name('staff.new');
  Route::post('register-staff', 'StaffController@registerStaff')->name('staff.register');
  Route::get('edit-staff/{id}', 'StaffController@editStaff')->name('staff.edit');
  Route::post('update-staff/{id}', 'StaffController@updateStaff')->name('staff.update');
  Route::post('activation-staff', 'StaffController@ActivationStaff')->name('staff.activation');
  Route::get('remove-staff/{id}', 'StaffController@removeStaff')->name('staff.remove');
  Route::get('staff-detail/{id}', 'StaffController@staffView')->name('staff.view');
  Route::post('staff-transection/{id}', 'StaffController@staffTransection')->name('staff.transection');

  //engineer
  Route::get('engineer', 'EngineerController@engineer')->name('engineer');
  Route::get('ajax-engineer', 'EngineerController@getEngineer')->name('engineer.list.ajax');
  Route::get('json-engineer', 'EngineerController@getEngineerJson')->name('engineer.list.json');
  Route::get('add-engineer', 'EngineerController@engineerNew')->name('engineer.new');
  Route::post('register-engineer', 'EngineerController@registerEngineer')->name('engineer.register');
  Route::get('edit-engineer/{id}', 'EngineerController@editEngineer')->name('engineer.edit');
  Route::post('update-engineer/{id}', 'EngineerController@updateEngineer')->name('engineer.update');
  Route::post('activation-engineer', 'EngineerController@ActivationEngineer')->name('engineer.activation');
  Route::get('remove-engineer/{id}', 'EngineerController@removeEngineer')->name('engineer.remove');
  Route::get('engineer-detail/{id}', 'EngineerController@engineerView')->name('engineer.view');
  Route::post('engineer-transection/{id}', 'EngineerController@engineerTransection')->name('engineer.transection');

  //karigar
  Route::get('karigar', 'KarigarController@karigar')->name('karigar');
  Route::get('ajax-karigar', 'KarigarController@getKarigar')->name('karigar.list.ajax');
  Route::get('json-karigar', 'KarigarController@getKarigarJson')->name('karigar.list.json');
  Route::get('add-karigar', 'KarigarController@karigarNew')->name('karigar.new');
  Route::post('register-karigar', 'KarigarController@registerKarigar')->name('karigar.register');
  Route::get('edit-karigar/{id}', 'KarigarController@editKarigar')->name('karigar.edit');
  Route::post('update-karigar/{id}', 'KarigarController@updateKarigar')->name('karigar.update');
  Route::post('activation-karigar', 'KarigarController@ActivationKarigar')->name('karigar.activation');
  Route::get('remove-karigar/{id}', 'KarigarController@removeKarigar')->name('karigar.remove');
  Route::get('karigar-detail/{id}', 'KarigarController@karigarView')->name('karigar.view');
  Route::post('karigar-transection/{id}', 'KarigarController@karigarTransection')->name('karigar.transection');
  Route::get('widless-amount/{id}', 'KarigarController@getWidLessAmount')->name('widless.amount.ajax');
  Route::get('info-widless-amount/{id}', 'KarigarController@infoWidLessAmount')->name('widless.amount.info');
  Route::get('remove-widless-amount/{id}', 'KarigarController@removeWidLessAmount')->name('widless.amount.remove');
  Route::post('register-widless-amount/{id}', 'KarigarController@registerWidLessAmount')->name('register.widless.amount');
  Route::post('update-widless-amount/{id}', 'KarigarController@updateWidLessAmount')->name('update.widless.amount');
  Route::get('salary-redirect/{id}', 'KarigarController@salaryRedirect')->name('karigar.salary.redirect');

  //karigar Salary
  Route::get('karigar-report/{id}/{month}/{year}', 'KarigarController@manageKarigarReport')->name('manage.karigar.report');
  Route::get('karigar-report-info/{id}', 'KarigarController@infoKarigarReport')->name('info.karigar.report');
  Route::post('karigar-payment/{id}/{month}/{year}', 'KarigarController@manageKarigarPayment')->name('manage.karigar.payment');
  Route::get('karigar-payment-delete/{id}', 'KarigarController@deleteKarigarPayment')->name('delete.karigar.payment');
  Route::post('update-karigar-report/{id?}/{month?}/{year?}', 'KarigarController@updateKarigarReport')->name('update.karigar.report');
  Route::post('update-karigar-salary/{id}', 'KarigarController@updateKarigarSalary')->name('update.karigar.salary');
  Route::post('register-karigar-report/{id}', 'KarigarController@registerKarigarReport')->name('register.karigar.report');
  Route::get('delete-karigar-report/{id}', 'KarigarController@deleteKarigarReport')->name('delete.karigar.report');

  //material
  Route::get('material', 'MaterialController@material')->name('material');
  Route::get('ajax-material', 'MaterialController@getMaterial')->name('material.list.ajax');
  Route::get('json-material', 'MaterialController@getMaterialJson')->name('material.list.json');
  Route::get('add-material', 'MaterialController@materialNew')->name('material.new');
  Route::post('register-material', 'MaterialController@registerMaterial')->name('material.register');
  Route::get('edit-material/{id}', 'MaterialController@editMaterial')->name('material.edit');
  Route::post('update-material/{id}', 'MaterialController@updateMaterial')->name('material.update');
  Route::post('activation-material', 'MaterialController@ActivationMaterial')->name('material.activation');
  Route::get('remove-material/{id}', 'MaterialController@removeMaterial')->name('material.remove');
  Route::get('material-detail/{id}', 'MaterialController@materialView')->name('material.view');
  Route::post('material-transection/{id}', 'MaterialController@materialTransection')->name('material.transection');

  //material Types
  Route::get('types-of-material', 'MaterialController@mtypes')->name('material.types');
  Route::get('get-types-of-material', 'MaterialController@getMtype')->name('material.types.get');
  Route::get('remove-types-of-material/{id}', 'MaterialController@removeMtype')->name('material.types.remove');
  Route::post('register-material-types', 'MaterialController@registerTypes')->name('material.types.register');
  Route::get('info-types-of-material', 'MaterialController@infoMtype')->name('material.types.info');
  Route::post('update-types-of-material', 'MaterialController@updateMtype')->name('material.types.update');

  //process
  Route::get('process', 'ProcessController@process')->name('process');
  Route::get('ajax-process', 'ProcessController@getProcess')->name('process.list.ajax');
  Route::get('json-process', 'ProcessController@getProcessJson')->name('process.list.json');
  Route::get('add-process', 'ProcessController@processNew')->name('process.new');
  Route::post('register-process', 'ProcessController@registerProcess')->name('process.register');
  Route::get('edit-process/{id}', 'ProcessController@editProcess')->name('process.edit');
  Route::post('update-process/{id}', 'ProcessController@updateProcess')->name('process.update');
  Route::post('register-new-process', 'ProcessController@newProcessRegister')->name('new.process.register');
  Route::post('update-new-process/{id}', 'ProcessController@newProcessUpdate')->name('new.process.update');
  Route::post('activation-process', 'ProcessController@ActivationProcess')->name('process.activation');
  Route::get('remove-process/{id}', 'ProcessController@removeProcess')->name('process.remove');
  Route::get('process-detail/{id}', 'ProcessController@processView')->name('process.view');
  Route::get('add-new-process', 'ProcessController@addNewProcess')->name('add.new.process');
  Route::get('edit-stock-process/{id}', 'ProcessController@editStockProcess')->name('edit.stock.process');
  Route::get('view-all-process', 'ProcessController@viewAllProcess')->name('view.all.process');
  Route::get('view-ajax-process', 'ProcessController@viewAjaxProcess')->name('view.ajax.process');
  Route::post('process-transection/{id}', 'ProcessController@processTransection')->name('process.transection');
  Route::get('process-payment-delete/{id}', 'ProcessController@deleteProcessPayment')->name('delete.process.payment');
  Route::get('process-delete/{id}', 'ProcessController@deleteProcessPaymentAll')->name('delete.stock.process');
  Route::get('download-process/{id}', 'ProcessController@downloadpdf')->name('process.pdf');
  Route::get('process-receive/{id}', 'ProcessController@receiveStock')->name('receive.process.stock');
  Route::post('register-process-receive/{id}', 'ProcessController@registerReceiveStock')->name('process.receive.register');


  //process types
  Route::get('types-of-process', 'ProcessController@mtypes')->name('process.types');
  Route::post('register-process-types', 'ProcessController@registerTypes')->name('process.types.register');
  Route::get('get-types-of-process', 'ProcessController@getMtype')->name('process.types.get');
  Route::get('remove-types-of-process/{id}', 'ProcessController@removeMtype')->name('process.types.remove');
  Route::get('info-types-of-process', 'ProcessController@infoMtype')->name('process.types.info');
  Route::post('update-types-of-process', 'ProcessController@updateMtype')->name('process.types.update');

  //expenses
  Route::get('expenses', 'ExpensesController@expenses')->name('expenses');
  Route::get('add-expenses', 'ExpensesController@addExpenses')->name('add.expenses');
  Route::get('add-category-info', 'ExpensesController@getCategoryInfo')->name('get.category.info');
  Route::post('update-expenses-category', 'ExpensesController@updateExpensesCategory')->name('update.expenses.category');
  Route::post('delete-expenses-category', 'ExpensesController@deleteExpensesCategory')->name('delete.expenses.category');
  Route::post('add-expenses-category', 'ExpensesController@addExpensesCategory')->name('add.expenses.category');
  Route::get('get-expenses-category', 'ExpensesController@getExpensesCategory')->name('get.expenses.category');
  Route::post('expenses-reigster', 'ExpensesController@expensesRegister')->name('register.expenses');
  Route::post('expenses-update/{id}', 'ExpensesController@expensesUpdate')->name('update.expenses');
  Route::get('get-expenses-ajax', 'ExpensesController@getExpensesAjax')->name('get.expenses.ajax');
  Route::get('manage-expenses/{id}', 'ExpensesController@editExpenses')->name('edit.expenses');
  Route::get('delete-expenses/{id}', 'ExpensesController@deleteExpenses')->name('delete.expenses');
  Route::get('expenses-payment-delete/{id}/{tid}', 'ExpensesController@deleteExpensesPayment')->name('delete.expenses.payment');

  //expenses unit
  Route::get('expenses-unit', 'ExpensesController@mtypes')->name('expenses.unit');
  Route::post('register-expenses-types', 'ExpensesController@registerTypes')->name('expenses.types.register');
  Route::get('get-types-of-expenses', 'ExpensesController@getMtype')->name('expenses.types.get');
  Route::get('remove-types-of-expenses/{id}', 'ExpensesController@removeMtype')->name('expenses.types.remove');
  Route::get('info-types-of-expenses', 'ExpensesController@infoMtype')->name('expenses.types.info');
  Route::post('update-types-of-expenses', 'ExpensesController@updateMtype')->name('expenses.types.update');

  //transport
  Route::get('transport', 'TransportController@transport')->name('transport');
  Route::get('ajax-transport', 'TransportController@getTransport')->name('transport.list.ajax');
  Route::get('json-transport', 'TransportController@getTransportJson')->name('transport.list.json');
  Route::get('add-transport', 'TransportController@transportNew')->name('transport.new');
  Route::post('register-transport', 'TransportController@registerTransport')->name('transport.register');
  Route::get('edit-transport/{id}', 'TransportController@editTransport')->name('transport.edit');
  Route::post('update-transport/{id}', 'TransportController@updateTransport')->name('transport.update');
  Route::post('activation-transport', 'TransportController@ActivationTransport')->name('transport.activation');
  Route::get('remove-transport/{id}', 'TransportController@removeTransport')->name('transport.remove');
  Route::get('transport-detail/{id}', 'TransportController@transportView')->name('transport.view');
  Route::post('transport-transection/{id}', 'TransportController@transportTransection')->name('transport.transection');


  //Machine
  Route::get('machine', 'MachineController@machine')->name('machine');
  Route::get('ajax-machine', 'MachineController@getMachine')->name('machine.list.ajax');
  Route::get('add-machine', 'MachineController@machineNew')->name('machine.new');
  Route::post('register-machine', 'MachineController@registerMachine')->name('machine.register');
  Route::get('edit-machine/{id}', 'MachineController@editMachine')->name('machine.edit');
  Route::post('update-machine/{id}', 'MachineController@updateMachine')->name('machine.update');
  Route::post('activation-machine', 'MachineController@ActivationMachine')->name('machine.activation');
  Route::get('remove-machine/{id}', 'MachineController@removeMachine')->name('machine.remove');

  //Bank Account
  Route::get('bank-account', 'BankController@bank')->name('bankaccount');
  Route::get('add-bank', 'BankController@newBank')->name('bank.new');
  Route::get('edit-bank/{id}', 'BankController@editBank')->name('bank.edit');
  Route::get('delete-bank/{id}', 'BankController@deleteBank')->name('bank.delete');
  Route::post('register-bank', 'BankController@registerBank')->name('bank.register');
  Route::post('update-bank/{id}', 'BankController@updateBank')->name('bank.update');
  Route::post('ajax-transection', 'BankController@getTransection')->name('bank.transection.ajax');
  Route::get('bank-adjustment', 'BankController@adjustmentBank')->name('bank.adjustment');
  Route::post('bank-adjustment-register', 'BankController@adjustmentBankRegister')->name('bank.adjustment.register');
  Route::get('edit-bank-adjustment/{id}', 'BankController@editAdjustmentBank')->name('bank.adjustment.edit');
  Route::get('delete-bank-adjustment/{id}', 'BankController@deleteAdjustmentBank')->name('bank.adjustment.delete');
  Route::post('bank-adjustment-update/{id}', 'BankController@adjustmentBankUpdate')->name('bank.adjustment.update');
  Route::post('ajax-excluding-bank', 'BankController@excludingBankList')->name('ajax.excluding.bank');

  //Banks list
  Route::get('bank-list', 'BankController@mtypes')->name('bank.unit');
  Route::post('register-bank-types', 'BankController@registerTypes')->name('bank.types.register');
  Route::get('get-types-of-bank', 'BankController@getMtype')->name('bank.types.get');
  Route::get('remove-types-of-bank/{id}', 'BankController@removeMtype')->name('bank.types.remove');
  Route::get('info-types-of-bank', 'BankController@infoMtype')->name('bank.types.info');
  Route::post('update-types-of-bank', 'BankController@updateMtype')->name('bank.types.update');

  //cash
  Route::get('cash-in-hand', 'CashController@cashInHand')->name('cashinhand');
  Route::post('ajax-cash-transection', 'CashController@getTransection')->name('cash.transection.ajax');
  Route::get('cash-adjustment', 'CashController@adjustmentCash')->name('cash.adjustment');
  Route::get('edit-cash-adjustment/{id}', 'CashController@adjustmentCashEdit')->name('cash.adjustment.edit');
  Route::post('cash-adjustment-register', 'CashController@adjustmentCashRegister')->name('cash.adjustment.register');
  Route::post('cash-adjustment-update/{id}', 'CashController@adjustmentCashUpdate')->name('cash.adjustment.update');
  Route::get('delete-cash-adjustment/{id}', 'CashController@deleteAdjustmentCash')->name('cash.adjustment.delete');

  //cheque
  Route::get('cheque', 'ChequeController@cheque')->name('cheque');
  Route::post('ajax-cheque-transection', 'ChequeController@getTransection')->name('cheque.transection.ajax');
  Route::get('cheque-adjustment/{id}', 'ChequeController@adjustmentCheque')->name('cheque.adjustment');
  Route::post('cheque-adjustment-register/{id}', 'ChequeController@adjustmentChequeRegister')->name('cheque.adjustment.register');
  Route::get('cheque-reopen/{id}', 'ChequeController@reopenCheque')->name('cheque.reopen');
  Route::get('delete-cheque/{id}', 'ChequeController@deleteCheque')->name('cheque.delete');

  //Payment in
  Route::get('payment-in', 'PaymentController@paymentIn')->name('paymentin');
  Route::get('edit-payment/{id}', 'PaymentController@editPayment')->name('edit.paymentin');
  Route::get('delete-payment/{id}', 'PaymentController@deletePayment')->name('delete.payment');
  Route::post('paymentin-register', 'PaymentController@paymentinRegister')->name('paymentin.register');
  Route::post('payment-update/{id}/{type}', 'PaymentController@paymentUpdate')->name('payment.update');

  //Payment out
  Route::get('payment-out', 'PaymentController@paymentOut')->name('paymentout');
  Route::post('paymentout-register', 'PaymentController@paymentoutRegister')->name('paymentout.register');

  //embroidery design
  Route::get('embroidery-design', 'DesignController@embroideryDesign')->name('embroidery.design');
  // Route::post('ajax-embroidery-design', 'DesignController@getEmbroideryDesign')->name('embroidery.design.ajax');
  Route::get('add-embroidery-design', 'DesignController@addEmbroiderydesign')->name('add.embroidery.design');
  Route::get('edit-embroidery-design/{id}', 'DesignController@editEmbroiderydesign')->name('edit.embroidery.design');
  Route::post('register-embroidery-design', 'DesignController@registerEmbroiderydesign')->name('register.embroidery.design');
  Route::post('update-embroidery-design/{id}', 'DesignController@updateEmbroiderydesign')->name('update.embroidery.design');
  Route::post('bookmark-design/{id}', 'DesignController@bookmarkDesign')->name('bookmark.design');
  Route::get('delete-design/{id}/{type}', 'DesignController@deleteDesign')->name('delete.design');

  //fashion design
  Route::get('fashion-design', 'DesignController@fashionDesign')->name('fashion.design');
  // Route::post('ajax-fashion-design', 'DesignController@getFashionDesign')->name('fashion.design.ajax');
  Route::get('add-fashion-design', 'DesignController@addFashiondesign')->name('add.fashion.design');
  Route::get('edit-fashion-design/{id}', 'DesignController@editFashiondesign')->name('edit.fashion.design');
  Route::post('register-fashion-design', 'DesignController@registerFashiondesign')->name('register.fashion.design');
  Route::post('update-fashion-design/{id}', 'DesignController@updateFashiondesign')->name('update.fashion.design');


  //programme card
  Route::get('programme-card', 'ProgrammeCardController@programmeCard')->name('programme.card');
  Route::get('ajax-programme-card', 'ProgrammeCardController@getPCAjax')->name('programme.card.ajax');
  Route::get('veirfy-stock-number', 'ProgrammeCardController@verifyStockNumber')->name('verify.stock.number');
  Route::get('manage-programme-card/{id?}', 'ProgrammeCardController@addProgrammeCard')->name('add.programme.card');
  Route::post('duplicate-programme-card/{id}', 'ProgrammeCardController@makeDuplicatePc')->name('make.duplicate.pc');
  Route::post('register-programme-card', 'ProgrammeCardController@registerProgrammeCard')->name('register.programme.card');
  Route::post('register-pc-design', 'ProgrammeCardController@registerPcDesign')->name('register.pc.design');
  Route::get('ajax-pc-design', 'ProgrammeCardController@getPCDesign')->name('pc.design.ajax');
  Route::get('get-inserted-qunatity', 'ProgrammeCardController@getTotalofInserted')->name('pc.quantity.total');
  Route::post('remove-pc-design/{id}', 'ProgrammeCardController@removePCDesign')->name('pc.design.remove');
  Route::post('pc-item-info/{id}', 'ProgrammeCardController@getPCItemInfo')->name('pc.item.info');
  Route::post('update-pc-item-quantity/{id}', 'ProgrammeCardController@updatePCQuantity')->name('pc.quantity.update');
  Route::get('edit-pc/{id}', 'ProgrammeCardController@editPC')->name('pc.edit');
  Route::get('remove-pc/{id}', 'ProgrammeCardController@removePC')->name('pc.remove');
  Route::get('download-programme-card/{id}', 'ProgrammeCardController@downloadpdf')->name('pc.pdf');
  Route::get('programme-card-receive/{id}', 'ProgrammeCardController@receiveStock')->name('receive.pc.stock');
  Route::post('register-programme-card-receive/{id}', 'ProgrammeCardController@registerReceiveStock')->name('pc.receive.register');

  //DailyProduction
  Route::get('daily-production', 'DailyProductionController@dailyProdution')->name('daily.production');
  Route::get('add-daily-production/{date?}', 'DailyProductionController@adddailyProdution')->name('add.daily.production');
  Route::post('register-daily-production', 'DailyProductionController@registerDailyProdution')->name('register.daily.production');
  Route::post('fetch-daily-production', 'DailyProductionController@fetchDailyProdution')->name('fetch.daily.production');
  Route::post('daily-production-ajax', 'DailyProductionController@dailyProdutionAjax')->name('daily.production.ajax');
  Route::get('delete-daily-production/{id}', 'DailyProductionController@deleteDailyProdution')->name('delete.daily.production');

  //frame report
  Route::get('manage-frame-report/{id}', 'FrameReportController@manageFrameReport')->name('manage.frame.report');
  Route::post('register-frame-report/{id}', 'FrameReportController@registerFrameReport')->name('register.frame.report');
  Route::post('fetch-frame-report', 'FrameReportController@fetchFrameReport')->name('fetch.frame.report');


  //delivery challan
  Route::get('delivery-challan', 'DeliveryController@deliverychallan')->name('delivery.challan');
  Route::get('add-delivery-challan', 'DeliveryController@addNewChallan')->name('add.delivery.challan');
  Route::post('register-delivery-challan', 'DeliveryController@newChallanRegister')->name('delivery.challan.register');
  Route::get('view-ajax-challan', 'DeliveryController@viewAjaxChallan')->name('view.ajax.challan');
  Route::get('challan-delete/{id}', 'DeliveryController@deleteChallan')->name('delete.delivery.challan');
  Route::get('edit-delivery-challan/{id}', 'DeliveryController@editChallan')->name('edit.delivery.challan');
  Route::post('update-delivery-challan/{id}', 'DeliveryController@updateDeliveryChallan')->name('update.delivery.challan');
  Route::get('download-delivery-challan/{id}', 'DeliveryController@downloadpdf')->name('delivery.challan.pdf');

  //Invoice
  Route::get('invoice', 'InvoiceController@deliverychallan')->name('invoice');
  Route::get('add-invoice', 'InvoiceController@addNewChallan')->name('add.invoice');
  Route::post('register-invoice', 'InvoiceController@newChallanRegister')->name('invoice.register');
  Route::get('view-ajax-invoice', 'InvoiceController@viewAjaxChallan')->name('view.ajax.invoice');
  Route::get('invoice-delete/{id}', 'InvoiceController@deleteChallan')->name('delete.invoice');
  Route::get('edit-invoice/{id}', 'InvoiceController@editChallan')->name('edit.invoice');
  Route::post('update-invoice/{id}', 'InvoiceController@updateDeliveryChallan')->name('update.invoice');
  Route::get('invoice-payment-delete/{id}', 'InvoiceController@deleteInvoicePayment')->name('delete.invoice.payment');
  Route::get('download-invoice/{id}', 'InvoiceController@downloadpdf')->name('invoice.pdf');
});
