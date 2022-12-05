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
$route['join'] = 'authentication/register';
$route['forget'] = 'authentication/resetView';
$route['verification'] = 'authentication/resetView';
$route['verify_otp'] = 'authentication/verify_otp';

$route['coming'] = 'admin/dashboard/coming';

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
$route['users/add_bank'] = 'user/UserController/add_bank';
$route['users/delete_member'] = 'user/UserController/delete_member';
$route['users/block_member'] = 'user/UserController/block_member';


// Setting groups

$route['setting'] = 'admin/role/index';
$route['setting/add'] = 'setting/Setting/add';
$route['setting/submit'] = 'setting/Setting/submit';
$route['setting/edit'] = 'setting/Setting/edit';
$route['setting/get_tabs'] = 'setting/Setting/get_tabs';

//recharge
$route['recharge'] = 'recharge/RechargeController/index';
$route['recharge/mobile'] = 'recharge/RechargeController/mobile';
$route['recharge/mobile/commission']='recharge/RechargeController/mobile_commission';
$route['recharge/dth/commission']='recharge/RechargeController/dth_commission';
$route['recharge/datacard/commission']='recharge/RechargeController/datacard_commission';
$route['recharge/landline/commission']='recharge/RechargeController/landline_commission';
$route['recharge/electricity/commission']='recharge/RechargeController/electricity_commission';
$route['recharge/gas/commission']='recharge/RechargeController/gas_commission';
$route['recharge/water/commission']='recharge/RechargeController/water_commission';
$route['recharge/insurance/commission']='recharge/RechargeController/insurance_commission';
$route['recharge/loan/commission']='recharge/RechargeController/loan_commission';
$route['recharge/dth'] = 'recharge/RechargeController/dth';
$route['recharge/datacard'] = 'recharge/RechargeController/datacard';
$route['recharge/landline'] = 'recharge/RechargeController/landline';
$route['recharge/electricity'] = 'recharge/RechargeController/electricity';
$route['recharge/gas'] = 'recharge/RechargeController/gas';
$route['recharge/fastag'] = 'recharge/RechargeController/fastag';

$route['recharge/water'] = 'recharge/RechargeController/water';
$route['recharge/insurance'] = 'recharge/RechargeController/insurance';
$route['recharge/get_mobile'] = 'recharge/RechargeController/get_mobile';
$route['recharge/fetch_plan'] = 'recharge/RechargeController/fetch_plan';
$route['recharge/mobile_submit'] = 'recharge/RechargeController/mobile_submit';
$route['recharge/fetch_dth_plan'] = 'recharge/RechargeController/fetch_dth_plan';
$route['recharge/fetch_bill'] = 'recharge/RechargeController/fetch_bill';
$route['recharge/bill_submit'] = 'recharge/RechargeController/bill_submit';
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
$route['wallet/deduct'] = 'wallet/Wallet/deduct';  
$route['wallet/credit'] = 'wallet/Wallet/credit'; 
$route['wallet/all_request'] = 'wallet/Wallet/all_request'; 
$route['wallet/summary'] = 'wallet/Wallet/summary'; 
$route['wallet/widthdraw'] = 'wallet/Wallet/widthdraw';

// DMT V2 route

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

// gst
$route['gstfiling/add'] = 'gstfiling/Gstfiling/add'; 
$route['gstfiling/submit'] = 'gstfiling/Gstfiling/submit'; 
$route['gstfiling/retailer_list'] = 'gstfiling/Gstfiling/retailer_list'; 
$route['gstfiling/gstform'] = 'gstfiling/Gstfiling/gstform'; 
$route['gstfiling/gstreturn'] = 'gstfiling/Gstfiling/gstreturn'; 
$route['gstfiling/reg'] = 'gstfiling/Gstfiling/reg'; 
$route['gstfiling/docs'] = 'gstfiling/Gstfiling/docs'; 
$route['gstfiling/saleReturn'] = 'gstfiling/Gstfiling/saleReturn'; 
$route['gstfiling/purchaseReturn'] = 'gstfiling/Gstfiling/saleReturn';
$route['gstfiling/gstreg_list'] = 'gstfiling/Gstfiling/gstreg_list'; 
$route['gstfiling/get_gstreg_list'] = 'gstfiling/Gstfiling/get_gstreg_list'; 
$route['gstfiling/pdfdetails'] = 'gstfiling/Gstfiling/pdfdetails';



// Paysprint Aeps Route
$route['aeps2'] = 'aeps2/PaySprintAepsController/index';
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