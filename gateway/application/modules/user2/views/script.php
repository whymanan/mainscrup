<script src="<?php echo base_url(ASSETS) ?>/vendor/datatables.net/js/jquery.dataTables.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/vendor/select2/dist/js/select2.min.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/js/form-validate.js" charset="utf-8"></script>
<script src="<?php echo base_url(ASSETS) ?>/js/kyc_validate.js" charset="utf-8"></script>

<script type="text/javascript">
  var Api = '<?php echo base_url('user/UserController/'); ?>';

  var duid = '<?php echo $this->session->userdata("user_id") ?>';

  var $squadlist = $('#squadlist');

  $(document).ready(function() {
       $.ajax({
        url:Api+'wallet_show',
        type:"GET",
        dataType:'JSON',
        success:function(data)
        {
           
            $('#balance').text(data.balance);
            $('.member').text(data.role);
        }
    });
      $("#role").change(function()
    {
       var role=this.value;
        $.ajax({
        url:Api+'wallet_show',
        type:"GET",
        data:{'value':role},
        dataType:'Json',
        success:function(data)
        {
            console.log(data.balance);
            console.log(data);
            $('#balance').text(data.balance);
            $('.member').text(data.role);
        }
       });
       $squadlist.DataTable().destroy()
       var $table = $squadlist.DataTable({
       "searching": false,
       "processing": true,
       "serverSide": true,
       "deferRender": true,
      
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No distributors data available ...",
      },
      "order": [],
      "ajax": {
        url: Api + "get_squadlist?key=" + duid + "&role="+role+"&list=all",
        type: "GET",
      },
      "pageLength": 10
    });
    })
    var $submit = $('form[name="validate"] :submit');


    $('input[name="phone_no"]').on('change', function() {

      var search = $(this).val();

      $.ajax({
        url: '<?php echo base_url('vendorexist'); ?>',
        type: 'GET',
        data: {
          'search': search,
        },
        beforeSend: function() {
          $('input[name="phone_no"]').parent().find('label').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          var result = JSON.parse(data);
          if (result.error) {
            $('input[name="phone_no"]').css({
              "border": "2px solid #ff5050",
              "color": "#ff5050",
            }).parent().find('label').css({
              "color": "#ff5050",
            }).html(result.msg);
            $submit.attr("disabled", "disabled");
          } else {
            $('input[name="phone_no"]').css({
              "border": "1px solid #2dce89",
              "color": "#2dce89",
            }).parent().append('<span><i class="zmdi zmdi-check"></i></span>');
            $('input[name="phone_no"]').parent().find('label').css({
              "color": "#525f7f",
            }).html('Mobile');
            $submit.removeAttr("disabled", "disabled");
          }
        },
        complete: function() {
          $('input[name="ifscCode"]').parent().find('img').remove();
        },
      })

    });

    $('#vendor').select2({
      ajax: {
        url: '<?php echo base_url('autovendor'); ?>',
        type: "GET",
        dataType: 'json',
        data: function(params) {
          var query = {
            search: params.term,
            role: duid,
            type: $('#type').val(),
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
          }
          return query;
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
      }
    });
 $('.pincode').on('change', function(){
      var states = '';
      var city = '';
      var pin = $(this).val();
      var area = [];
      $.ajax({
        url: 'https://api.postalpincode.in/pincode/'+pin, // pincode to city , states list api
        type: 'GET',
        beforeSend: function() {
          $('input[name="pincode"]').parent().find('label').append('<span><img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" /></span>');
        },
        success: function(data) {
          $.each(data, function(index, value){
            if (value.Status === "Success") {
              $.each(value.PostOffice, function(index, value){
                states = value.State;
                city = value.District;
                area[index] = value.Name;
              });
              $('.states').html("<option selected value="+states+">"+states+"</option>");
              $('.cities').html("<option selected value="+city+">"+city+"</option>");
              html = '';
              $.each(area, function(index, value){
                html += "<option value="+value+">"+value+"</option>";
              });
              $('.area').html(html);
            }else{
            }
          });
        },
        complete: function() {
          $('input[name="pincode"]').parent().find('span').remove();
        },
      })

    });

    // $('#cities').select2({
    //   minimumInputLength: 3, // only start searching when the user has input 3 or more characters
    //   ajax: {
    //     url: '<?php echo base_url('cities'); ?>',
    //     type: "GET",
    //     dataType: 'json',
    //     data: function(params) {
    //       var query = {
    //         search: params.term,
    //         type: 'public',
    //         "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
    //       }
    //       return query;
    //     },
    //     processResults: function(data) {
    //       return {
    //         results: data
    //       };
    //     },
    //     // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    //   }
    // });
    // Select states list
    $('#role').select2({
      ajax: {
        url: '<?php echo base_url('autorole'); ?>',
        type: "GET",
        dataType: 'json',
        data: function(params) {
          var query = {
            search: params.term,
            type: 'public',
            "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
          }
          return query;
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
      }
    });

    // $('.cities').select2({
    //   ajax: {
    //     url: '<?php echo base_url('cities'); ?>',
    //     type: "GET",
    //     dataType: 'json',
    //     data: function(params) {
    //       var query = {
    //         search: params.term,
    //         type: 'public',
    //         "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
    //       }
    //       return query;
    //     },
    //     processResults: function(data) {
    //       return {
    //         results: data
    //       };
    //     },
    //     // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    //   }
    // });
    // Select states list
    // $('.states').select2({
    //   ajax: {
    //     url: '<?php echo base_url('states'); ?>',
    //     type: "GET",
    //     dataType: 'json',
    //     data: function(params) {
    //       var query = {
    //         search: params.term,
    //         type: 'public',
    //         "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>",
    //       }
    //       return query;
    //     },
    //     processResults: function(data) {
    //       return {
    //         results: data
    //       };
    //     },
    //     // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    //   }
    // });

    // distributor list
 $('#copy').on('change', function() {
      $(this).attr('checked', this.checked)
      if ($(this).prop('checked') == true) {
          
          var temp = $('#s_state').val();
          
        $('#HAddress').val($('#s_address').val());
        
        var data = {
            id: 1,
            text: 'Barn owl'
        };
        
        var newOption = new Option(data.text, data.id, false, false);
        $('#h_state').append(newOption).trigger('change');
        
        // $('select[name="home_states"] option[value="'+temp+'"]').attr('selected','selected'); 
        $('#h_city').val($('#s_city').val()); 
        $('#h_pincode').val($('#s_pincode').val());
      } else {
        $('#HAddress').val("");
        $('#h_state').val("");
        $('#h_city').val("");
        $('#h_pincode').val("");
      }

    });


    var $table = $squadlist.DataTable({
      "searching": false,
      "processing": true,
      "serverSide": true,
      "deferRender": true,
      "language": {
        "processing": '<img width="24" height="24" src="<?php echo base_url('optimum/loading.svg') ?>" />',
        "emptyTable": "No distributors data available ...",
      },
      "order": [],
      "ajax": {
        url: Api + "get_squadlist?key=" + duid + "&list=all",
        type: "GET",
      },

      "pageLength": 10
    });

 $("#simplefilter").click(function(event) {
      event.preventDefault();
     
    url = Api + "get_squadlist?key=" + duid + "&searchValue"+  $('#searchValue').val() +"&searchByCat=" + $('#searchByCat').val() + "&search=simple&list=all";
       $table.ajax.url(url).load();

    });
$("#clear").click(function(event) {
      event.preventDefault();
     
   url = Api + "get_squadlist?key=" + duid + "&list=all";
       $table.ajax.url(url).load();

    });

    $('.node').click(function() {

      var tab = $(this).attr('data-info');
      var url = '';
      switch (tab) {
        case 'all':
          url = Api + "get_squadlist?key=" + duid + "&list=all";
          break;
        case 'active':
          url = Api + "get_squadlist?key=" + duid + "&list=verify";
          break;
        case 'new':
          url = Api + "get_squadlist?key=" + duid + "&list=new";
          break;
        case 'pending':

          url = Api + "get_squadlist?key=" + duid + "&list=pending";

          break;
        default:
          url = Api + "get_squadlist?key=0&list=null";

      }

      $table.ajax.url(url).load();

    });

    $('.refresh').on('click', function() {
      $table.ajax.reload();
      angular.element(document.querySelector('[ng-controller="executor"]')).scope().live();

    });

  });

  EmoApp.controller('squad', function($scope, $http) {
    $scope.active = "";
    $scope.all = "";
    $scope.new = "";
    $scope.pending = "";
    $scope.live = function() {
      $http({
        method: 'GET',
        url: Api + 'live_count?key=' + duid,
      }).then(function success(response) {
        console.log(response);
        $scope.active = response.data.verify;
        $scope.all = response.data.all;
        $scope.new = response.data.new;
        $scope.pending = response.data.pending;


      }, function error(response) {

        // this function will be called when the request returned error status
        $scope.active = 'N/A';
        $scope.all = 'N/A';
        $scope.new = 'N/A';
        $scope.pending = 'N/A';
      });
    }
    $scope.live();
  });
</script>




<!--certificate js open-->

<script src="https://unpkg.com/pdf-lib@1.4.0"></script>
<input type="hidden" id="name" name="" value="<?php echo $this->session->userdata('user_name')?>">
<input type="hidden" id="member_id" name="" value="<?php echo $this->session->userdata('member_id')?>">
<input type="hidden" id="user_roles" name="" value="<?php echo $this->session->userdata('user_roles')?>">
<script>
    (function (global, factory) {
        if (typeof define === "function" && define.amd) {
          define([], factory);
        } else if (typeof exports !== "undefined") {
          factory();
        } else {
          var mod = {
            exports: {}
          };
          factory();
          global.FileSaver = mod.exports;
        }
      })(this, function () {
        "use strict";
      
        /*
        * FileSaver.js
        * A saveAs() FileSaver implementation.
        *
        * By Eli Grey, http://eligrey.com
        *
        * License : https://github.com/eligrey/FileSaver.js/blob/master/LICENSE.md (MIT)
        * source  : http://purl.eligrey.com/github/FileSaver.js
        */
        // The one and only way of getting global scope in all environments
        // https://stackoverflow.com/q/3277182/1008999
        var _global = typeof window === 'object' && window.window === window ? window : typeof self === 'object' && self.self === self ? self : typeof global === 'object' && global.global === global ? global : void 0;
      
        function bom(blob, opts) {
          if (typeof opts === 'undefined') opts = {
            autoBom: false
          };else if (typeof opts !== 'object') {
            console.warn('Deprecated: Expected third argument to be a object');
            opts = {
              autoBom: !opts
            };
          } // prepend BOM for UTF-8 XML and text/* types (including HTML)
          // note: your browser will automatically convert UTF-16 U+FEFF to EF BB BF
      
          if (opts.autoBom && /^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(blob.type)) {
            return new Blob([String.fromCharCode(0xFEFF), blob], {
              type: blob.type
            });
          }
      
          return blob;
        }
      
        function download(url, name, opts) {
          var xhr = new XMLHttpRequest();
          xhr.open('GET', url);
          xhr.responseType = 'blob';
      
          xhr.onload = function () {
            saveAs(xhr.response, name, opts);
          };
      
          xhr.onerror = function () {
            console.error('could not download file');
          };
      
          xhr.send();
        }
      
        function corsEnabled(url) {
          var xhr = new XMLHttpRequest(); // use sync to avoid popup blocker
      
          xhr.open('HEAD', url, false);
      
          try {
            xhr.send();
          } catch (e) {}
      
          return xhr.status >= 200 && xhr.status <= 299;
        } // `a.click()` doesn't work for all browsers (#465)
      
      
        function click(node) {
          try {
            node.dispatchEvent(new MouseEvent('click'));
          } catch (e) {
            var evt = document.createEvent('MouseEvents');
            evt.initMouseEvent('click', true, true, window, 0, 0, 0, 80, 20, false, false, false, false, 0, null);
            node.dispatchEvent(evt);
          }
        } // Detect WebView inside a native macOS app by ruling out all browsers
        // We just need to check for 'Safari' because all other browsers (besides Firefox) include that too
        // https://www.whatismybrowser.com/guides/the-latest-user-agent/macos
      
      
        var isMacOSWebView = /Macintosh/.test(navigator.userAgent) && /AppleWebKit/.test(navigator.userAgent) && !/Safari/.test(navigator.userAgent);
        var saveAs = _global.saveAs || ( // probably in some web worker
        typeof window !== 'object' || window !== _global ? function saveAs() {}
        /* noop */
        // Use download attribute first if possible (#193 Lumia mobile) unless this is a macOS WebView
        : 'download' in HTMLAnchorElement.prototype && !isMacOSWebView ? function saveAs(blob, name, opts) {
          var URL = _global.URL || _global.webkitURL;
          var a = document.createElement('a');
          name = name || blob.name || 'download';
          a.download = name;
          a.rel = 'noopener'; // tabnabbing
          // TODO: detect chrome extensions & packaged apps
          // a.target = '_blank'
      
          if (typeof blob === 'string') {
            // Support regular links
            a.href = blob;
      
            if (a.origin !== location.origin) {
              corsEnabled(a.href) ? download(blob, name, opts) : click(a, a.target = '_blank');
            } else {
              click(a);
            }
          } else {
            // Support blobs
            a.href = URL.createObjectURL(blob);
            setTimeout(function () {
              URL.revokeObjectURL(a.href);
            }, 4E4); // 40s
      
            setTimeout(function () {
              click(a);
            }, 0);
          }
        } // Use msSaveOrOpenBlob as a second approach
        : 'msSaveOrOpenBlob' in navigator ? function saveAs(blob, name, opts) {
          name = name || blob.name || 'download';
      
          if (typeof blob === 'string') {
            if (corsEnabled(blob)) {
              download(blob, name, opts);
            } else {
              var a = document.createElement('a');
              a.href = blob;
              a.target = '_blank';
              setTimeout(function () {
                click(a);
              });
            }
          } else {
            navigator.msSaveOrOpenBlob(bom(blob, opts), name);
          }
        } // Fallback to using FileReader and a popup
        : function saveAs(blob, name, opts, popup) {
          // Open a popup immediately do go around popup blocker
          // Mostly only available on user interaction and the fileReader is async so...
          popup = popup || open('', '_blank');
      
          if (popup) {
            popup.document.title = popup.document.body.innerText = 'downloading...';
          }
      
          if (typeof blob === 'string') return download(blob, name, opts);
          var force = blob.type === 'application/octet-stream';
      
          var isSafari = /constructor/i.test(_global.HTMLElement) || _global.safari;
      
          var isChromeIOS = /CriOS\/[\d]+/.test(navigator.userAgent);
      
          if ((isChromeIOS || force && isSafari || isMacOSWebView) && typeof FileReader !== 'undefined') {
            // Safari doesn't allow downloading of blob URLs
            var reader = new FileReader();
      
            reader.onloadend = function () {
              var url = reader.result;
              url = isChromeIOS ? url : url.replace(/^data:[^;]*;/, 'data:attachment/file;');
              if (popup) popup.location.href = url;else location = url;
              popup = null; // reverse-tabnabbing #460
            };
      
            reader.readAsDataURL(blob);
          } else {
            var URL = _global.URL || _global.webkitURL;
            var url = URL.createObjectURL(blob);
            if (popup) popup.location = url;else location.href = url;
            popup = null; // reverse-tabnabbing #460
      
            setTimeout(function () {
              URL.revokeObjectURL(url);
            }, 4E4); // 40s
          }
        });
        _global.saveAs = saveAs.saveAs = saveAs;
      
        if (typeof module !== 'undefined') {
          module.exports = saveAs;
        }
      });
  </script>
<script src="https://unpkg.com/pdf-lib/dist/pdf-lib.min.js"></script>
<script src="https://unpkg.com/@pdf-lib/fontkit@0.0.4"></script>
<script>
   const userName =document.getElementById("name").value;
   const member_id = document.getElementById("member_id").value;
   const user_roles = document.getElementById("user_roles").value;
   const getcert = document.getElementById("getcert");
   

   
   
   const { PDFDocument, rgb, degrees } = PDFLib;
   
   
//    const capitalize = (str, lower = false) =>
//        (lower ? str.toLowerCase() : str).replace(/(?:^|\s|["'([{])+\S/g, (match) =>
//            match.toUpperCase()
//        );
   
    getcert.addEventListener("click", () => {
    //    const val = capitalize(userName.value);
    //     consoloe.log(val);
       //check if the text is empty or not
    //    if (val.trim() !== "" && userName.checkValidity()) {
    //        // console.log(val);
    //        generatePDF(val);
    //    } else {
    //        userName.reportValidity();
    //    }
    generatePDF(userName,member_id);
   });
   
   const generatePDF = async(name,id) => {
       const { PDFDocument, rgb, degrees } = PDFLib;
       var pdffile;
       if(user_roles==97)//master distributor pdf
       {
         pdffile="<?php echo base_url('optimum/rbwmd2.pdf')?>";
       }
       else if(user_roles==95)// distributor pdf
       {
         pdffile="<?php echo base_url('optimum/rbwd2.pdf')?>";
       }
       else//master retailer pdf
       {
         pdffile="<?php echo base_url('optimum/rbwrt2.pdf')?>";
       }
   
       const existingPdfBytes = await fetch(pdffile).then((res) => {
           return res.arrayBuffer()
       });
       // Load a PDFDocument from the existing PDF bytes
       const pdfDoc = await PDFDocument.load(existingPdfBytes);
       pdfDoc.registerFontkit(fontkit);
   
       //get font
       const fontBytes = await fetch("<?php echo base_url('optimum/Poppins-Medium.ttf')?>").then((res) =>
           res.arrayBuffer()
       );
   
       // Embed our custom font in the document
       const SanChezFont = await pdfDoc.embedFont(fontBytes);
   
       // Get the first page of the document
       const pages = pdfDoc.getPages();
       const firstPage = pages[0];
   
       // Draw a string of text diagonally across the first page
       firstPage.drawText(name, {
           x: 45,
           y: 340,
           size: 42,
           font: SanChezFont,
           color: rgb(0.5, 0.2, 0.6),
       });
       firstPage.drawText(id, {
           x: 50,
           y: 320,
           size: 16,
           font: SanChezFont,
           color: rgb(0.5, 0.2, 0.6),
       });
   
       // Serialize the PDFDocument to bytes (a Uint8Array)
       const pdfBytes = await pdfDoc.save();
       console.log("Done creating");
   
       // this was for creating uri and showing in iframe
   
    //    const pdfDataUri = await pdfDoc.saveAsBase64({ dataUri: true });
    //    document.getElementById("deepak").src = pdfDataUri;
   
       var file = new File(
           [pdfBytes],
           "RBWISH Certificate.pdf", {
               type: "application/pdf;charset=utf-8",
           }
       );
       saveAs(file);
   };
   // init();
</script>
<!--certificate js close-->