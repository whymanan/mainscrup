var count=0;
var $biomatric = {};
// function RDService(){

function CheckDevice(){

  var url = "http://127.0.0.1:11100";


  var xhr;
  var res;
  var ua = window.navigator.userAgent;
  var msie = ua.indexOf("MSIE ");

	if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
	{
		//IE browser
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}else {
		//other browser
		xhr = new XMLHttpRequest();
	}

		$.ajax({
			type: "RDSERVICE",
			async: false,
			crossDomain: true,
			url: url,
			contentType: "text/xml; charset=utf-8",
			processData: false,
			cache: false,
			crossDomain: true,
			success: function(data) {
			  httpStaus = true;
			  res = {
				httpStaus: httpStaus,
				data: ParseXml(data),
				url: url
			  };
			},
			error: function(jqXHR, ajaxOptions, thrownError) {
			  // if (i == "8005" && OldPort == true) {
			  //   OldPort = false;
			  //   i = "11099";
			  // }
			},
		  });

		return res;

}

function DeviceInfo()
{

  var url = "http://127.0.0.1:11100/getDeviceInfo";

         var xhr;
			var ua = window.navigator.userAgent;
			var msie = ua.indexOf("MSIE ");

			if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
			{
				//IE browser
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			} else {
				//other browser
				xhr = new XMLHttpRequest();
			}
        
        //
        xhr.open('DEVICEINFO', url, true);

         xhr.onreadystatechange = function () {
		// if(xhr.readyState == 1 && count == 0){
		//	fakeCall();
		//}
		if (xhr.readyState == 4){
            var status = xhr.status;

            if (status == 200) {

                alert(xhr.responseText);
				   		 
	            console.log(xhr.response);

            } else 
			{
                
	            console.log(xhr.response);

            }
			}

        };

	 xhr.send();


}

function Capturemorpho()
{

  	var url = "http://127.0.0.1:11100/capture";

   	var XML='<PidOptions ver=\"1.0\">'+'<Opts fCount=\"1\" fType=\"0\" iCount=\"\" iType=\"\" pCount=\"\" pType=\"\" format=\"0\" pidVer=\"2.0\" timeout=\"10000\" otp=\"\" wadh=\"\" posh=\"\"/>'+'</PidOptions>';
 
 	var xhr;
			var ua = window.navigator.userAgent;
			var msie = ua.indexOf("MSIE ");

			if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
			{
				//IE browser
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			} else {
				//other browser
				xhr = new XMLHttpRequest();
			}
        

		var res;

		$.ajax({
			type: "CAPTURE",
			async: false,
			crossDomain: true,
			url: url,
			data: XML,
			contentType: "text/xml",
			dataType: "text",
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
			alert(thrownError);
			res = {
				httpStaus: httpStaus,
				err: getHttpError(jqXHR)
			};
			},
		});

		return res;
	
}

function getPosition(string, subString, index) {
  return string.split(subString, index).join(subString).length;
}

function fakeCall(){
 var xhr1;
  var url = 'http://127.0.0.1:11100/getDeviceInfo';

			var ua = window.navigator.userAgent;
			var msie = ua.indexOf("MSIE ");

			if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
			{
				//IE browser
				xhr1 = new ActiveXObject("Microsoft.XMLHTTP");
			} else {
				//other browser
				xhr1 = new XMLHttpRequest();
			}
        
        xhr1.open('DEVICEINFO', url, true);
		xhr1.send(); 
		count =1;
		xhr1.onreadystatechange = function () {
		if(xhr1.readyState == 4){
			xhr1.abort();
		}
		};
}

function ParseXml(data){

	var info = { };
	  var CmbData1 =  $(data).find('RDService').attr('status');
	  var CmbData2 =  $(data).find('RDService').attr('info');
	  
	  if($(data).find('Interface').attr('path') == "/127.0.0.1:11100/capture")
	  var CmbData3 =  "/capture";

	  info = {
		status: CmbData1,
		info: CmbData2,
		methodCapture: CmbData3,
	  };
	
	return info;
  
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

