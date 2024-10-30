jQuery(document).ready(function(){

setTimeout(function(){

			jQuery('#idonateTable').DataTable({
			  "columns": [
					null,
					null,
					{ "orderable": false }
				  ]
			  });
			
			/* tooltip */
			
			jQuery('.iDonatetrigger').tipso({
				position: 'right',
				background: 'rgb(242,242,242)',
				color: '#000',
				maxWidth: 300,
				width: 250,
				tooltipHover: true,
				titleContent : 'Latest Update !!',
				titleBackground : 'rgb(0,146,212)',
				useTitle: false,
			});
			
			/* tooltip */

/* Store session code */

jQuery(".savesession").click(function(){
		
		console.log();
		var classes = jQuery(this).attr('class');
		
		classes = classes.split(" ");
		
		window.location = classes[1];
		
		/*jQuery.ajax({
				url: 'storesession.php',
				type: 'POST',
				data: {
					page_name: 'viewfundraisingpage',
					mydonationpage: 'viewfundraisingpage',
					pid: pid
				},
				dataType : 'json',
				success: function(data, textStatus, xhr) {
					$("#loading").css('display','none');
					document.location.href = domain+"/donor-sign-in-charity.html";
					//console.log(data); // do with data e.g success message
				},
				error: function(xhr, textStatus, errorThrown) {
					$("#loading").css('display','none');
					document.location.href = domain+"/donor-sign-in-charity.html";
					//console.log(textStatus.reponseText);
				}
   		 });*/
		
	
});

/* Store session code */



},100);

setTimeout(function(){


jQuery(".iDonateboxouter").mCustomScrollbar({
	axis:"x",
	theme:"3d-dark",
	scrollButtons:{enable:true}
});

jQuery(".iDonateFundDetailContentMainintro").mCustomScrollbar({
	axis:"x",
	theme:"3d-dark",
	scrollButtons:{enable:true}
});


// Get elements.

var preLoadIconOn = function () {
          var newE = document.createElement("div"),
              newB = document.createElement("div");
              newE.setAttribute("id", "simplbox-loading");
              newE.appendChild(newB);
          document.body.appendChild(newE);
      },
      preLoadIconOff = function () {
          var elE = document.getElementById("simplbox-loading");
          elE.parentNode.removeChild(elE);
      },
      overlayOn = function () {
          var newA = document.createElement("div");
          newA.setAttribute("id", "simplbox-overlay");
          document.body.appendChild(newA);
      },
      overlayOff = function () {
          var elA = document.getElementById("simplbox-overlay");
          elA.parentNode.removeChild(elA);
      },
      closeButtonOn = function (base) {
          var newE = document.createElement("div");
          newE.setAttribute("id", "simplbox-close");
          document.body.appendChild(newE);
          newE = document.getElementById("simplbox-close");
          base.API_AddEvent(newE, "click touchend", function () {
              base.API_RemoveImageElement();
              return false;
          });
      },
      closeButtonOff = function () {
          var elA = document.getElementById("simplbox-close");
          elA.parentNode.removeChild(elA);
      },
      captionOn = function (base) {
          var element = document.getElementById("simplbox-caption"),
              documentFragment = document.createDocumentFragment(),
              newElement = document.createElement("div"),
              newText = document.createTextNode(base.m_Alt);

          if (element) {
              element.parentNode.removeChild(element);
          }

          newElement.setAttribute("id", "simplbox-caption");
          newElement.appendChild(newText);
          documentFragment.appendChild(newElement);
          document.getElementsByTagName("body")[0].appendChild(documentFragment);
      },
      captionOff = function () {
          var element = document.getElementById("simplbox-caption");
          element.parentNode.removeChild(element);
      };

var myelement = document.querySelectorAll('[data-simplbox]');
// Get constructor.
var simplbox = new SimplBox(myelement, {
	      quitOnDocumentClick: false,
          onStart: function () {
              overlayOn();
              closeButtonOn(simplbox);
          },
          onEnd: function () {
              overlayOff();
              closeButtonOff();
          },
          onImageLoadStart: preLoadIconOn,
          onImageLoadEnd: preLoadIconOff
      });

// Initialize.
simplbox.init();	

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=287550318019453&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

jQuery('body').prepend('<div id=\"fb-root\"></div>\n<script><\/script>\n');
	

},3000);


	
});