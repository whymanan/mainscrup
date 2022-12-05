<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//For pages those have a static name
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['logout'] = 'authentication/logout';
$route['login'] = 'authentication/authentication/login';
$route['auth'] = 'authentication/authentication/index';
$route['auth/get_data'] = 'authentication/authentication/get_data';
$route['sms'] = 'authentication/authentication/sms';
$route['otp_verify'] = 'authentication/authentication/otp_verify';
$route['join'] = 'authentication/register';
$route['forget'] = 'authentication/resetView';
$route['verification'] = 'authentication/resetView';
$route['auth/remove_otp/(:any)']="authentication/authentication/remove_otp/$1";
$route['auth/resend_otp']="authentication/authentication/resend_otp";
$route['verify_otp'] = 'authentication/verify_otp';
$route['reset_pass'] = 'authentication/reset_pass';
$route['user/notify'] = 'user/UserController/manual_notification';
$route['user/send/notify'] = 'user/UserController/notify';
$route['user/otp_send'] = 'user/UserController/send_otp';
$route['user/otp_verify'] = 'user/UserController/otp_verify';

$route['coming'] = 'admin/dashboard/coming';
// for cron job controller
$route['cronjob'] = 'cronjob/status_checker';
$route['cronjob/bill'] = 'cronjob/statusbill_checker';

$route['slider'] = 'admin/Dashboard/slider';
$route['slideradd'] = 'admin/Dashboard/slideradd';
$route['edit/(:any)'] = 'admin/Dashboard/editslider/$1';
$route['slideredit/(:any)'] = 'admin/Dashboard/edit/$1';
$route['delete/(:any)'] = 'admin/Dashboard/delete/$1';
// bank
$route['bank/commission']='bank/Axis_bank/bank_commission';
$route['bank/distribute_commission']='bank/Axis_bank/distribute_commission';
$route['bank/share_commission']='bank/Axis_bank/share_commission_account';
$route['bank/axis-bank-account'] = "bank/Axis_bank/index";
$route['bank/generated-axis-account'] = "bank/Axis_bank/generated_axis_account";
// User group

$route['users'] = 'user/UserController/index';
$route['users/add'] = 'user/UserController/add';
$route['users/submit'] = 'user/UserController/submit';
$route['users/edit'] = 'user/UserController/edit';
$route['users/update'] = 'user/UserController/update';
$route['users/live_count'] = 'user/UserController/live_count';
$route['users/file_upload'] = 'user/UserController/file_upload';
$route['profile'] = 'user/UserController/profile';
$route['users/kyc'] = 'user/UserController/kyc';
$route['users/pan_verify'] = 'user/UserController/pan_verify';
$route['users/add_bank'] = 'user/UserController/add_bank';

$route['users/checkpass/(:any)'] = 'user/UserController/checkpass/$1';
$route['users/resetpass'] = 'user/UserController/resetpass';

// Setting groups

$route['setting'] = 'admin/role/index';
$route['setting/add'] = 'setting/Setting/add';
$route['setting/submit'] = 'setting/Setting/submit';
$route['setting/edit'] = 'setting/Setting/edit';
$route['setting/get_tabs'] = 'setting/Setting/get_tabs';

//recharge
$route['recharge'] = 'recharge/RechargeController/index';
$route['recharge/mobile'] = 'recharge/RechargeController/mobile';
$route['recharge/status_checker'] = 'recharge/RechargeController/status_checker';
$route['recharge/vishal'] = 'recharge/RechargeController/vishal';
$route['recharge/broadband'] = 'recharge/RechargeController/broadband';
$route['recharge/emi'] = 'recharge/RechargeController/emi';
$route['recharge/hospital'] = 'recharge/RechargeController/hospital';
$route['recharge/cable'] = 'recharge/RechargeController/cable';
$route['recharge/challan'] = 'recharge/RechargeController/challan';
$route['recharge/municipaltax'] = 'recharge/RechargeController/municipaltax';
$route['recharge/municipality'] = 'recharge/RechargeController/municipality';
$route['recharge/mobile/commission']='recharge/RechargeController/mobile_commission';
$route['recharge/retailer/commission']='recharge/RechargeController/retailer_show_commission';
$route['recharge/dth/commission']='recharge/RechargeController/dth_commission';
$route['recharge/datacard/commission']='recharge/RechargeController/datacard_commission';
$route['recharge/Recharge_Postpaid/commission']='recharge/RechargeController/mobilep_commission';
$route['recharge/landline/commission']='recharge/RechargeController/landline_commission';
$route['recharge/electricity/commission']='recharge/RechargeController/electricity_commission';
$route['recharge/gas/commission']='recharge/RechargeController/gas_commission';
$route['recharge/water/commission']='recharge/RechargeController/water_commission';
$route['recharge/insurance/commission']='recharge/RechargeController/insurance_commission';
$route['recharge/loan/commission']='recharge/RechargeController/loan_commission';
$route['recharge/emi/commission']='recharge/RechargeController/emi_commission';
$route['recharge/broadband/commission']='recharge/RechargeController/broadband_commission';
$route['recharge/cable/commission']='recharge/RechargeController/cable_commission';
$route['recharge/challan/commission']='recharge/RechargeController/challan_commission';
$route['recharge/municipaltax/commission']='recharge/RechargeController/municipaltax_commission';
$route['recharge/Municipality/commission']='recharge/RechargeController/Municipality_commission';
$route['recharge/dth'] = 'recharge/RechargeController/dth';
$route['recharge/datacard'] = 'recharge/RechargeController/datacard';
$route['recharge/landline'] = 'recharge/RechargeController/landline';
$route['recharge/electricity'] = 'recharge/RechargeController/electricity';
$route['recharge/gas'] = 'recharge/RechargeController/gas';
$route['recharge/fastag'] = 'recharge/RechargeController/fastag';
$route['recharge/mobile_history'] = 'recharge/RechargeController/mobile_history';
$route['recharge/history'] = 'recharge/RechargeController/history';

$route['recharge/water'] = 'recharge/RechargeController/water';
$route['recharge/insurance'] = 'recharge/RechargeController/insurance';
$route['recharge/get_mobile'] = 'recharge/RechargeController/get_mobile';
$route['recharge/fetch_plan'] = 'recharge/RechargeController/fetch_plan';
$route['recharge/mobile_submit'] = 'recharge/RechargeController/mobile_submit';
$route['recharge/dth_submit'] = 'recharge/RechargeController/dth_submit';
$route['recharge/dthinfo'] = 'recharge/RechargeController/dthinfo';
$route['recharge/fetch_dth_plan'] = 'recharge/RechargeController/fetch_dth_plan';
$route['recharge/fetch_bill'] = 'recharge/RechargeController/fetch_bill';
$route['recharge/fetch_bill2'] = 'recharge/RechargeController/fetch_bill2';//mitra
$route['recharge/bill_submit'] = 'recharge/RechargeController/bill_submit';
$route['recharge/electricity_submit'] = 'recharge/RechargeController/electricity_submit';//mitra
// Commission groups

$route['commission'] = 'aeps/aepsController/Commission';
$route['Commision/add'] = 'commision/Commission/add';
$route['commision/submit'] = 'commision/Commission/submit';
$route['commision/edit'] = 'commision/Commission/edit';
$route['commision/view'] = 'commision/Commission/view';
$route['commission/get_slab'] = 'commision/commission/get_slab';
// services groups

$route['services'] = 'setting/services/index';
$route['service/submit'] = 'setting/services/submit';
// Slab groups

$route['slab'] = 'setting/slab/index';
$route['slab/add'] = 'setting/slab/add';
$route['slab/submit'] = 'setting/slab/submit';
//kyc
$route['kyc'] = 'kyc/kyc/index';
$route['kyc/edit'] = 'kyc/kyc/edit';
$route['kyc/profile'] = 'kyc/kyc/profile';
$route['kyc/createxls'] = 'kyc/Kyc/createxls';
$route['dashboard'] = 'admin/dashboard';
$route['kyc/bank'] = 'kyc/kyc/bank';
$route['kyc/get_info'] = 'kyc/kyc/get_info';
// Aeps Route
$route['aeps'] = 'aeps/aepsController/index';
$route['aeps/transection'] = 'aeps/aepsController/aepsTransectionForm';
$route['aeps/biometric'] = 'aeps/aepsController/aepsBiometricForm';
$route['aeps/submitTransection'] = 'aeps/aepsController/submitTransection';
$route['smssend'] = 'aeps/aepsController/smssend';
$route['bankList'] = 'aeps/aepsController/bankList';
$route['getlist'] = 'aeps/aepsController/getlist';
$route['aeps/thistory'] = 'aeps/aepsController/history';
$route['aeps/history'] = 'aeps/aepsController/get_history';
$route['aeps/surcharge'] = 'aeps/aepsController/surcharge';
$route['aeps/add_surcharge'] = 'aeps/aepsController/add_surcharge';
$route['aeps/insert'] = 'aeps/aepsController/insert';





// Distributor
$route['menu'] = 'admin/menu/index';
$route['menu/add'] = 'admin/menu/add';
$route['menu/update'] = 'admin/menu/update';
$route['menu/submit'] = 'admin/menu/submit';
$route['menu/update'] = 'admin/menu/update';




// role
$route['role'] = 'admin/role/index';
$route['role/edit'] = 'admin/role/edit';
$route['role/submit'] = 'admin/role/submit';
$route['role/get_tabs'] = 'admin/role/get_tabs';
$route['role/update_role_permission'] = 'admin/role/update_role_permission';
$route['role/update_role_permission2'] = 'admin/role/update_role_permission2';


$route['autocities'] = 'external/auto_cities';
$route['autostates'] = 'external/auto_states';
$route['cities'] = 'external/get_cities';
$route['states'] = 'external/get_states';
$route['ifscdata'] = 'external/datayuge';
$route['autovendor'] = 'external/get_vendor';
$route['autovendor_balance'] = 'external/auto_vendor';
$route['autoservice'] = 'external/get_service';

$route['autorole'] = 'external/get_role';
$route['vendorexist'] = 'external/get_vendor_exist';
$route['role_exist'] = 'external/get_role_exist';
$route['menuexist'] = 'external/get_menu_exist';
$route['submenuexist'] = 'external/get_submenu_exist';
$route['file_upload'] = 'file_upload/index';
$route['gallery_upload'] = 'file_upload/add_gallery';
$route['smssend'] = 'external/smssend';

//superadmin 
$route['superadmin'] = 'superadmin/superadmin/admin_list';
$route['superadmin/admin_list'] = 'superadmin/superadmin/admin_list';  
$route['superadmin/add'] = 'superadmin/superadmin/add';  
$route['superadmin/summary'] = 'superadmin/superadmin/summary'; 
// $route['superadmin/view_commision'] = 'superadmin/superadmin/view_commision';
$route['superadmin/addcommission'] = 'superadmin/superadmin/addcommission';
$route['superadmin/editCommission'] = 'superadmin/superadmin/editCommission';
$route['superadmin/thistory'] = 'superadmin/superadmin/thistory';
$route['superadmin/commission_form'] = 'superadmin/superadmin/commission_form';
$route['superadmin/admindetails'] = 'superadmin/superadmin/admindetails';

// wallet
$route['wallet'] = 'wallet/Wallet/index';
$route['wallet/fund_request'] = 'wallet/Wallet/fund_request_v';
$route['wallet/deduct'] = 'wallet/Wallet/deduct';  
$route['wallet/credit'] = 'wallet/Wallet/credit'; 
$route['wallet/all_request'] = 'wallet/Wallet/all_request'; 
$route['wallet/summary'] = 'wallet/Wallet/summary'; 
$route['wallet/widthdraw'] = 'wallet/Wallet/widthdraw';
$route['wallet/otp_send'] = 'wallet/Wallet/send_otp';
$route['wallet/otp_verify'] = 'wallet/Wallet/otp_verify';
$route['balance/transfer'] = 'wallet/Wallet/balance_tranfer_sumbmit';
$route['balance'] = 'wallet/Wallet/balance_tranfer';

// DMT V2 route
$route['dmtv2/verify'] = 'dmtv2/DmtvtwoController/varify';
$route['dmtv2'] = 'dmtv2/DmtvtwoController/index';
$route['dmtv2/customers'] = 'dmtv2/DmtvtwoController/customers';
$route['dmtv2/addCustomer'] = 'dmtv2/DmtvtwoController/addCustomer';
$route['dmtv2/customerOtpVarify/(:any)/(:any)'] = 'dmtv2/DmtvtwoController/customerVerification/$1/$2';
$route['dmtv2/beneficiaryOtpVarify/(:any)/(:any)'] = 'dmtv2/DmtvtwoController/beneficiaryVerification/$1/$2';
$route['dmtv2/submitBeneficiaryForm'] = 'dmtv2/DmtvtwoController/submitBeneficiaryForm';
$route['dmtv2/addBeneficiary'] = 'dmtv2/DmtvtwoController/addBeneficiary';
$route['dmtv2/history'] = 'dmtv2/DmtvtwoController/history';
$route['dmtv2/commission'] = 'dmtv2/DmtvtwoController/commission';
$route['dmtv2/insert'] = 'dmtv2/DmtvtwoController/insert';
$route['dmtv2/surcharge'] = 'dmtv2/DmtvtwoController/surcharge';
$route['dmtv2/add_surcharge'] = 'dmtv2/DmtvtwoController/add_surcharge';
$route['dmtv2/dmtTForm'] = 'dmtv2/DmtvtwoController/dmtTForm';
$route['dmtv2/mTransfer'] = 'dmtv2/DmtvtwoController/submitTransection';
$route['dmtv2/print/(:any)'] = 'dmtv2/DmtvtwoController/print/$1';




// DMT route

$route['dmt'] = 'dmt/DmtController/index';
$route['dmt/addBeneficiaryForm'] = 'dmt/DmtController/dmtAddBeneficiaryForm';
$route['dmt/submitBeneficiaryForm'] = 'dmt/DmtController/submitBeneficiaryForm';
$route['dmt/commission'] = 'dmt/DmtController/commission';
$route['dmt/insert'] = 'dmt/DmtController/insert';
$route['dmt/history'] = 'dmt/DmtController/thistory';
$route['dmt/commision'] = 'dmt/DmtController/commission';
$route['dmt/surcharge'] = 'dmt/DmtController/surcharge';
$route['dmt/add_surcharge'] = 'dmt/DmtController/add_surcharge';
$route['dmt/get_surcharge_value'] = 'dmt/DmtController/get_surcharge_value';
$route['dmt/verify_account'] = 'dmt/DmtController/verify_account';
// bank
$route['detail'] = 'bank/bank/index';
$route['bank/send_otp'] = 'bank/bank/send_otp';
$route['bank/otp_verify'] = 'bank/bank/otp_verify';

// legal service
// gst
$route['legal/gst'] = 'legalservice/LegalController/index';
$route['legal/gst/pay'] = 'legalservice/LegalController/payment';
$route['legal/gst/pay/save'] = 'legalservice/LegalController/payment_save';
$route['legal/gst/list'] = 'legalservice/LegalController/list'; 
$route['legal/gst/return/list'] = 'legalservice/LegalController/list_return';
$route['legal/itr/salary/list'] = 'legalservice/LegalController/list_itr_salary';
$route['legal/itr/bussiness/list'] = 'legalservice/LegalController/list_itr_bussiness';
$route['legal/company/list'] = 'legalservice/LegalController/list_company';
$route['legal/msme/list'] = 'legalservice/LegalController/list_msme';
$route['legal/gst/view_return'] = 'legalservice/LegalController/view_return';
$route['legal/gst/itr_salary_view'] = 'legalservice/LegalController/itr_salary_view';
$route['legal/gst/msme_view'] = 'legalservice/LegalController/msme_view';
$route['legal/gst/company_view'] = 'legalservice/LegalController/view_comapmy';
$route['legal/gst/itr_bussiness_view'] = 'legalservice/LegalController/itr_bussiness_view';
$route['legal/gst/view'] = 'legalservice/LegalController/view';
$route['legal/gst/price'] = 'legalservice/LegalController/gst_price';
$route['legal/gst/return'] = 'legalservice/LegalController/gst_return';
$route['legal/gst/company_price'] = 'legalservice/LegalController/company_price/';
$route['legal/gst/company_registation'] = 'legalservice/LegalController/company_registation/';
$route['legal/gst/itr/bussiness'] = 'legalservice/LegalController/itrbussiness';
$route['legal/gst/itr/salary'] = 'legalservice/LegalController/itrsalary';
$route['legal/gst/msme/registration'] = 'legalservice/LegalController/msme';
$route['legal/gst/comission'] = 'legalservice/LegalController/commission';



// Paysprint Aeps Route
$route['aeps2'] = 'aeps2/PaySprintAepsController/index';
$route['aeps2/aadharpay'] = 'aeps2/PaySprintAepsController/aadhar_pay';
$route['aeps/transection2'] = 'aeps2/PaySprintAepsController/aepsTransectionForm';
$route['aeps/biometric2'] = 'aeps2/PaySprintAepsController/aepsBiometricForm';
$route['aeps/submitTransection2'] = 'aeps2/PaySprintAepsController/submitTransection';
$route['smssend2'] = 'aeps2/PaySprintAepsController/smssend';
$route['bankList2'] = 'aeps2/PaySprintAepsController/bankList';
$route['getlist2'] = 'aeps2/PaySprintAepsController/getlist';
$route['aeps/thistory2'] = 'aeps2/PaySprintAepsController/history';
$route['aeps/history2'] = 'aeps2/PaySprintAepsController/get_history';
$route['aeps/surcharge2'] = 'aeps2/PaySprintAepsController/surcharge';
$route['aeps/add_surcharge2'] = 'aeps2/PaySprintAepsController/add_surcharge';
$route['aeps/insert2'] = 'aeps2/PaySprintAepsController/insert';
$route['aeps/onboarding'] = 'aeps2/PaySprintAepsController/onboarding';
$route['aeps/submitonboarding'] = 'aeps2/PaySprintAepsController/onboardsubmit';
$route['aeps2/aadharpaycharge'] = 'aeps2/PaySprintAepsController/aadharpaycharge';
$route['aeps2/commission'] = 'aeps2/PaySprintAepsController/commission';
$route['aeps2/print/(:any)'] = 'aeps2/PaySprintAepsController/print/$1';

$route['aeps2/download'] = 'aeps2/PaySprintAepsController/driver_download';

    
// Pancard Routers
$route['pancard/AgentRegistration'] = 'pancard/pancard/registration';
$route['pancard/BuyCoupon'] = 'pancard/pancard/coupon_request';
$route['pancard/commission'] = 'pancard/pancard/commission';
$route['pancard/Cinsert'] = 'pancard/pancard/Cinsert';
$route['pancard/AmountSet'] = 'pancard/pancard/AmountSet';