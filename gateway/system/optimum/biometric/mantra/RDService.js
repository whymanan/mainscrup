var GetCustomDomName = '127.0.0.1';
var PrimaryUrl='';

var $biomatric = {};



function discoverAvdm() {
  // New
  GetCustomDomName = "127.0.0.1";
  PrimaryUrl = "http://" + GetCustomDomName + ":";

  try {
    var protocol = window.location.href;
    if (protocol.indexOf("https") >= 0) {
      PrimaryUrl = "https://" + GetCustomDomName + ":";
    }
  } catch (e) {}

  //alert("Please wait while discovering port from 11100 to 11120.\nThis will take some time.");
  SuccessFlag = 0;
  for (var i = 11100; i <= 11102; i++) {
    // if (PrimaryUrl == "https://" + GetCustomDomName + ":" && OldPort == true) {
    //   i = "8005";
    // }

    var verb = "RDSERVICE";
    var err = "";

    var res;
    $.support.cors = true;
    var httpStaus = false;
    var jsonstr = "";
    var data = new Object();
    var obj = new Object();



    $.ajax({
      type: "RDSERVICE",
      async: false,
      crossDomain: true,
      url: PrimaryUrl + i.toString(),
      contentType: "text/xml; charset=utf-8",
      processData: false,
      cache: false,
      crossDomain: true,
      success: function(data) {
        httpStaus = true;
        res = {
          httpStaus: httpStaus,
          data: ParseXml(data),
          url: PrimaryUrl + i.toString()
        };
        SuccessFlag = 1;
      },
      error: function(jqXHR, ajaxOptions, thrownError) {
        // if (i == "8005" && OldPort == true) {
        //   OldPort = false;
        //   i = "11099";
        // }
      },
    });
  }

  return res;

}



function CaptureAvdm(PrimaryUrl) {
  var res;

  var XML = '<PidOptions ver="1.0"> <Opts fCount="1" fType="0" iCount="0" pCount="0" pgCount="2" format="0"   pidVer="2.0" timeout="10000" pTimeout="20000" posh="UNKNOWN" env="PP" /> <CustOpts><Param name="mantrakey" value="" /></CustOpts> </PidOptions>';
  $.ajax({
    type: "CAPTURE",
    async: false,
    crossDomain: true,
    url: PrimaryUrl,
    data: XML,
    contentType: "text/xml; charset=utf-8",
    processData: false,
    success: function(data) {
      httpStaus = true;

      xmlToJson($.parseXML( data ));

      res = {
        httpStaus: httpStaus,
        data: data,
        biomatric: $biomatric
      };
    },
    error: function(jqXHR, ajaxOptions, thrownError) {
      //$('#txtPidOptions').val(XML);
      alert(thrownError);
      res = {
        httpStaus: httpStaus,
        err: getHttpError(jqXHR)
      };
    },
  });

  return res;
}


function deviceInfoAvdm(PrimaryUrl)	{
  var res;
  $.ajax({
  		type: "DEVICEINFO",
  		async: false,
  		crossDomain: true,
  		url: PrimaryUrl,
  		contentType: "text/xml; charset=utf-8",
  		processData: false,
  		success: function (data) {
  		//alert(data);
  			httpStaus = true;
  			res = { httpStaus: httpStaus, data: data };
  		},
  		error: function (jqXHR, ajaxOptions, thrownError) {
  		alert(thrownError);
  			res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
  		},
  });
  return res;
}

function xmlToJson(xml) {
    // Create the return object
    var obj = {};
    if (xml.nodeType == 1) { // element
      if (xml.tagName == 'Hmac') {
        $biomatric['hmac'] = xml.innerHTML;
      }
      if(xml.tagName == "Skey"){
        $biomatric['sessionKey'] = xml.innerHTML;
      }
      if (xml.tagName == "Data") {
        $biomatric['piddata'] = xml.innerHTML;
      }
      // do attributes
      if (xml.attributes.length > 0) {
        for (var j = 0; j < xml.attributes.length; j++) {
          var attribute = xml.attributes.item(j);
          if (attribute.nodeName == "name") {
            $biomatric[attribute.nodeValue] = "";
            var value = xml.attributes.item(j+1);
            $biomatric[attribute.nodeValue] = value.nodeValue;
          }else if(attribute.nodeName == "value"){
          }else if (attribute.nodeName == "dpId") {
            $biomatric['dpID'] = attribute.nodeValue;
          }else if (attribute.nodeName == "rdsId") {
            $biomatric['rdsID'] = attribute.nodeValue;
          }else if (attribute.nodeName == "type") {
            $biomatric['pidDatatype'] = attribute.nodeValue;
          }else{
            $biomatric[attribute.nodeName] = attribute.nodeValue;
          }
        }
      }
    } else if (xml.nodeType == 3) { // text
      obj = xml.nodeValue;
    }

    // do children
    if (xml.hasChildNodes()) {
      for(var i = 0; i < xml.childNodes.length; i++) {
        var item = xml.childNodes.item(i);
        var nodeName = item.nodeName;
        if (typeof(obj[nodeName]) == "undefined") {
          obj[nodeName] = xmlToJson(item);
        } else {
          if (typeof(obj[nodeName].push) == "undefined") {
            var old = obj[nodeName];
            obj[nodeName] = [];
            obj[nodeName].push(old);
          }
          obj[nodeName].push(xmlToJson(item));
        }
      }
    }
  }



function getHttpError(jqXHR) {
  var err = "Unhandled Exception";
  if (jqXHR.status === 0) {
    err = 'Service Unavailable';
  } else if (jqXHR.status == 404) {
    err = 'Requested page not found';
  } else if (jqXHR.status == 500) {
    err = 'Internal Server Error';
  } else if (thrownError === 'parsererror') {
    err = 'Requested JSON parse failed';
  } else if (thrownError === 'timeout') {
    err = 'Time out error';
  } else if (thrownError === 'abort') {
    err = 'Ajax request aborted';
  } else {
    err = 'Unhandled Error';
  }
  return err;
}


function ParseXml(data){
  var info = { };
  var $doc = $.parseXML(data);
	var CmbData1 =  $($doc).find('RDService').attr('status');
	var CmbData2 =  $($doc).find('RDService').attr('info');
	if(RegExp('\\b'+ 'Mantra' +'\\b').test(CmbData2)==true)
	{

		if($($doc).find('Interface').eq(0).attr('path')=="/rd/capture")
		{
		  MethodCapture=$($doc).find('Interface').eq(0).attr('path');
		}
		if($($doc).find('Interface').eq(1).attr('path')=="/rd/capture")
		{
		  MethodCapture=$($doc).find('Interface').eq(1).attr('path');
		}
		if($($doc).find('Interface').eq(0).attr('path')=="/rd/info")
		{
		  MethodInfo=$($doc).find('Interface').eq(0).attr('path');
		}
		if($($doc).find('Interface').eq(1).attr('path')=="/rd/info")
		{
		  MethodInfo=$($doc).find('Interface').eq(1).attr('path');
		}
    info = {
      status: CmbData1,
      info: CmbData2,
      methodCapture: MethodCapture,
      methodInfo: MethodInfo
    };
  }
  return info;

}
