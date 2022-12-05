$(function(){
    var form = $('form[name="validate"]');

    $.validator.addMethod("phoneno", function(phone_number, element) {
        	    phone_number = phone_number.replace(/\s+/g, "");
        	    return this.optional(element) || phone_number.length == 10 &&
        	    phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
      }, "<br />Enter valid phone number");
    $.validator.addMethod("adharno", function(adharno, element) {
        	    adharno = adharno.replace(/\s+/g, "");
        	    return this.optional(element) || adharno.length > 11 &&
        	    adharno.match(/^\d{4}\d{4}\d{4}$/);
      }, "<br />Enter valid Adhar number");
    $.validator.addMethod("pancard", function(pancard, element) {
        	    pancard = pancard.replace(/\s+/g, "");
        	    return this.optional(element) || pancard.length > 9 &&
        	    pancard.match(/[a-zA-z]{5}\d{4}[a-zA-Z]{1}$/);
      }, "<br />Enter valid PAN Card number");

    form.validate({
      errorPlacement: function errorPlacement(error, element) { element.before(error); },
      rules: {
        // simple rule, converted to {required:true}
        business_name: {
          required: true,
        },
        // compound rule
        business_address: {
          required: true,
        },
        business_owner_name: {
          required: true,
        },
        landmark: {
          required: true,
        },
        email: {
          required: false,
          email: true
        },
        phone_no: {
          required:true,
          phoneno: true
        },
        pincode: {
          required: true,
          minlength:6,
          maxlength:6,
        },
        city: {
          required: true,

        },
        states: {
          required: true,

        },
        adharcard: {
          required:true,
          adharno: true
        },
        pancard: {
          required:true,
          pancard: true
        },
        permanent_address: {
          required: true,
        },
        account_name: {
          required: true,
        },
        account_number: {
          required: true,
        },
        bank:{
            required: true,
        },
        branch:{
            required: true,
        }
      },
      messages: {
        business_name: "Please specify Business Name",
        business_address: "Please specify Business Address",
        permanent_address: "Please specify Permanent Address",
        business_owner_name: "Please specify owner Name",
        account_name: "Please specify Account Holder Name",
        account_number: "Please specify Account Number",
        email: {
          required: "We need your email address to contact you",
          email: "Your email address must be in the format of name@domain.com"
        },
        phone_no : {
          required: 'Phone must be entered',
          minlength: 'Enter valide phone number',
          maxlength: 'Enter valide phone number',
        },
        pincode : {
          required: 'Pincode must be entered',
          minlength: 'Enter valide Pincode number',
          maxlength: 'Enter valide Pincode number',
        }
      },
      submitHandler: function(form) {
         form.submit();
      }
    });
});
