<?php

function iDonateD_getFundList() {

	$iDonateD_checkActive = iDonateD_getDbAccess();	
	$iDonateD_explodeDiv = explode('|',$iDonateD_checkActive); 
	$iDonateD_active = $iDonateD_explodeDiv[0];
	$iDonateD_chid = $iDonateD_explodeDiv[1];
	/* For fundraiser Detail page */
	
	if(iDonateD_is_connected() == 1) {

	if($_REQUEST['FundPageid'] != '') {
	
	$fundidis = base64_decode(base64_decode($_REQUEST['FundPageid']));

	$fundidis = explode('!!', $fundidis);
	
	$fundidis = $fundidis[0];
	
	if($fundidis != '') {
		$FundDetaildata = file_get_contents(iDonateD_DOMAIN."api/getFundraiserDetail?page_id=".$fundidis);
		$FundDetaildata = json_decode($FundDetaildata, true); // Turns it into an array, change the last argument to false to make it an object
	} else {
		$FundDetaildata = '';
		iDonateD_remove_my_shortcode( $tag );
	}
	

	$db_widgets = $FundDetaildata['fundraiserDetail']['widgets'];
	$arrWidget = explode(" ",$db_widgets);

		foreach($arrWidget as $wid)
			{	//echo $wid;
				if($wid == "event"){
					$displayEvent = 'style="display:block"';
				}
				if($wid == "video"){
					$displayVideo = 'style="display:block;"';
				}	
				if($wid == "share"){
					$displayShare = 'style="display:block;"';
				}	
				if($wid == "twitter"){
					$displayTwitter = 'style="display:block;"';
				}	
				if($wid == "blog"){
					$displayBlog = 'style="display:block;"';
				}	
				if($wid == "gallery"){
					$displayGallery = 'style="display:block;"';
				}
			}

	$listDisplay = '';
	/* Main Div */
		$listDisplay .= '<div id="iDonateMain" class="col-xs-12 col-md-12 col-sm-12">';
		/* Main Page Title H2 */
		$listDisplay .= '<h2 class="iDonateFundDetailH2 col-xs-12 col-md-12 col-sm-12">';
		
		$listDisplay .= $FundDetaildata['fundraiserDetail']['page_title'];
		
		$listDisplay .= '</h2>';
			/* Main Content Div */
			$listDisplay .= '<div class="iDonateFundDetailContent"> ';
				/* Content Wrapper */
				$listDisplay .= '<div class="iDonateFundDetailContentWrapper"> ';
					/* Content Wrapper Middle */
					$listDisplay .= '<div class="iDonateFundDetailContentWrapperMiddle iDonte_content_in">';
					
					/* Introduction Blue box content */
					$listDisplay .= '<div class="iDonateFundDetailContentMainintro">';
						
						/* Profile Info div */
						$listDisplay .= '<div class="iDonateFundDetailContentProfile_info">';
							
							/* Left content div in profile */
							$listDisplay .= '<div class="iDonateFundDetailContentLeft-profile">';
								
								/* Profile Main image div */
								$listDisplay .= '<div class="iDonateFundDetailContentProfile_image">';
									
									/* Profile Main image */
									$file_headers = @get_headers($FundDetaildata['fundraiserDetail']['profile_picture']);
									if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
										$FundDetaildata['fundraiserDetail']['profile_picture'] = iDonateD_DOMAIN.'images/no-image-avaliable.gif';
									}
									$listDisplay .= '<img class="iDonateProfile_img" width="164" alt="" src="'.$FundDetaildata['fundraiserDetail']['profile_picture'].'" style="border: medium none;">';
									$listDisplay .= '<br>';
									
									/* blue arrow */
									$listDisplay .= '<div class="iDonatebluearw"></div>';
										
										/* Short Status div */
										$listDisplay .= '<div class="iDonateevent_status">';
											$listDisplay .= '<strong>Latest Update !!</strong>';
											$listDisplay .= '<div class="iDonatebubbleInfo">';
												if(strlen($FundDetaildata['fundraiserDetail']['short_status']) > 70) { $statusdesh = '...';}
												
												$shortState = substr(strip_tags(html_entity_decode($FundDetaildata['fundraiserDetail']['short_status'])),0,65);
												$listDisplay .= $shortState.$statusdesh;
											
											if(strlen($FundDetaildata['fundraiserDetail']['short_status']) > 70) {
												$listDisplay .= '<div class="iDonatetrigger" data-tipso="'.$FundDetaildata['fundraiserDetail']['short_status'].'" ><a><strong>Read more</strong></a></div>';
											}	
											
											$listDisplay .= '</div>';
										$listDisplay .= '</div>';
										
										
											if($FundDetaildata['fundraiserDetail']['daysDuration'] > 0)
											{
												$listDisplay .= '<div class="iDonatebtnDV iDonatesponsor left" style="margin:10px 0 10px 2px;">';

												$listDisplay .= '<a class="savesession '.iDonateD_DOMAIN.'/api/getRedirectDonation?pageid='.base64_encode(base64_encode($FundDetaildata['fundraiserDetail']['fundraiser_id']).'|'.base64_encode('viewfundraisingpage')).' " href="javascript:">Sponsor Me</a><br />';

												$listDisplay .= '</div>';

											}
										
									//<div class="bluearw"></div>
								$listDisplay .= '</div>';
							$listDisplay .= '</div>';
							
							/* Right Profile */
							$listDisplay .= '<div class="iDonateFundDetailContentRight-profile">';
								/* Profile Owner Name */
								$listDisplay .= '<h2 class="iDonateFundDetailContentRight-profileH2">';
									$listDisplay .= $FundDetaildata['fundraiserDetail']['name'];
								$listDisplay .= '</h2>';

							/* Clean div */
							$listDisplay .= '<div class="iDonateclearDiv"></div>';	
							
							$listDisplay .= '<div class="iDonatecevent_details">';
								$listDisplay .= '<h3 class="iDonatecevent_detailsH3">Event Details</h3>';
									$listDisplay .= '<div class="iDonateevent_details_left">';
									
										$listDisplay .= '<label><strong>Event Name/Type :</strong></label><br />';
										$listDisplay .= $FundDetaildata['fundraiserDetail']['events']['eventname'];
										
										$listDisplay .= '<br /><br /><label><strong>Event Start Date :</strong></label><br />';
										$listDisplay .= $FundDetaildata['fundraiserDetail']['events']['eventStartDate'];
										
									$listDisplay .= '</div>';
									
									$listDisplay .= '<div class="iDonateevent_details_right">';
									
										$listDisplay .= '<label><strong>Donations Available Until :</strong></label><br />';
										$listDisplay .= $FundDetaildata['fundraiserDetail']['events']['DonationAvailable'];
										
										$listDisplay .= '<br /><br /><label><strong>Location :</strong></label><br />';
										$listDisplay .= $FundDetaildata['fundraiserDetail']['events']['eventlocation'];
										
									$listDisplay .= '</div>';	
								
							$listDisplay .= '</div>';
							
								/* Event Link Left */
								$listDisplay .= '<div class="iDonateevent_link_left">';

									if($FundDetaildata['fundraiserDetail']['events']['eventID'] != '') {
											$file_headers_event = @get_headers($FundDetaildata['fundraiserDetail']['events']['eventIcon']);
											if($file_headers_event[0] == 'HTTP/1.1 404 Not Found') {
												$FundDetaildata['fundraiserDetail']['events']['eventIcon'] = iDonateD_DOMAIN.'images/no-image-avaliable.gif';
											}

										 $listDisplay .= '<img align="left" src="'.$FundDetaildata['fundraiserDetail']['events']['eventIcon'].'" height="32" width="32" alt="Charity icon" style="border:1px solid #CCC;"/>';
										 $listDisplay .= '<div class="iDonatebtnDV left" style="margin:8px 0 0 0"> <a target="_blank" style="text-decoration:none;" href="'.$FundDetaildata['fundraiserDetail']['events']['eventlink'].'" title="Get more information about '.$FundDetaildata['fundraiserDetail']['events']['eventname'].'"> View Event Details</a></div>';
									} else {
									 	$listDisplay .=  '<h3> Please Support My Cause</h3>';
									}
								
								$listDisplay .= '</div>';	
								/* Event Link Left */
							$listDisplay .= '</div>';

							/* Running Total */
							$listDisplay .= '<div class="iDonaterunning-total"> <h3 class="iDonaterunning-totalH3">Running Total<span> &euro;'.$FundDetaildata['fundraiserDetail']['runningtotal']['runningamount'].'</span></h3>';
							
							$listDisplay .= '<div class="iDonateclearDiv"></div>';
							
							$listDisplay .= '<div class="iDonateleftDiv">';
								$listDisplay .= '<div class=\"iDonatebottolMain\" style="float:left; width:31px; height:200px; margin:0; padding:0">';
								
								$listDisplay .= '<img style="float:left; margin: -7px 0 0; padding: 0 0 0 5px; width: auto;" src="'.$FundDetaildata['fundraiserDetail']['runningtotal']['topimage'].'" alt="Top" title="Top" />';
								
								$listDisplay .= '<div class=\"iDonatebottolTop\" style="height:100%; width:23px; margin:0 0 0 5px; background:url('.$FundDetaildata['fundraiserDetail']['runningtotal']['blueJpg'].') repeat-y;">';
								$listDisplay .= '<div style="height:'.$FundDetaildata['fundraiserDetail']['runningtotal']['final_per'].'%; background:url('.$FundDetaildata['fundraiserDetail']['runningtotal']['whiteJpg'].') repeat-y; width:27px; margin:0 0 0 0; "> </div>';
								
								$listDisplay .= '</div>';
								
								$listDisplay .= '<img src="'.$FundDetaildata['fundraiserDetail']['runningtotal']['bottomimage'].'" /></div>';
								
								$listDisplay .= "<div class=\"iDonatebottolMid\" style=\"float:left; font-size:11px; color:#333; font-family:Arial, Helvetica, sans-serif; width:31px; height:200px; margin:0; padding:0\">\n"; 
									$listDisplay .= "                      <div style=\"padding:0 0 0 0; height: 18.18px;\">- 100</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px; \">- 90</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 80</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 70</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 60</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 50</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 40</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 30</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 20</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 10</div>\n"; 
									$listDisplay .= "                      <div style=\"padding:3px 0 0 0; height: 18.18px;\">- 0</div>\n"; 
								$listDisplay .= "                    </div>\n"; 
							$listDisplay .= "                  </div>\n";
								
								/* Target Details */
								$listDisplay .= '<div class="iDonatetargetDetail">';
										$listDisplay .=  "<dl>\n"; 
										$listDisplay .=  "                      <!-- HUNAR: Make target editable -->\n"; 
										$listDisplay .=  "                      <dt><strong>Target : &nbsp;</strong> </dt>\n"; 
										$listDisplay .=  "                      <dd>\n"; 
										$listDisplay .=  "                        <div class=\"target\"> ".$FundDetaildata['fundraiserDetail']['runningtotal']['targetamount']." </div>\n"; 
										$listDisplay .=  "                      </dd>\n"; 
										$listDisplay .=  "                    </dl>\n";
								
										$listDisplay .= "<dl>\n"; 
										$listDisplay .= "                      <!-- Hunar target editable change over -->\n"; 
										$listDisplay .= "                      <dt><strong>Total Donors :&nbsp;</strong> </dt>\n"; 
										$listDisplay .= "                      <dd>\n"; 
										$listDisplay .= "                        ".$FundDetaildata['fundraiserDetail']['runningtotal']['totalDonors']."\n"; 
										$listDisplay .= "                        <br />\n"; 
										$listDisplay .= "                        <br />\n"; 
										$listDisplay .= "                      </dd>\n"; 
										$listDisplay .= "                    </dl>\n";
								
										$listDisplay .=  "<dl>\n"; 
										$listDisplay .=  "                      <dt><strong>Biggest Donor :&nbsp;</strong> </dt>\n"; 
										$listDisplay .=  "                      <dd>\n"; 
										$listDisplay .=  "					 ".$FundDetaildata['fundraiserDetail']['runningtotal']['biggestdonor']."\n"; 
										$listDisplay .=  "                        <br />\n"; 
										$listDisplay .=  "                      </dd>\n"; 
										$listDisplay .=  "                    </dl>\n";
										
										$listDisplay .= "<dl>\n"; 
										$listDisplay .= "                      <dt><strong>Last Donor :&nbsp;</strong> </dt>\n"; 
										$listDisplay .= "                      <dd> ".$FundDetaildata['fundraiserDetail']['runningtotal']['lastdonor']." <br />\n"; 
										$listDisplay .= "                        <br />\n"; 
										$listDisplay .= "                      </dd>\n"; 
										$listDisplay .= "                      <dt><strong>Offline Fundraising : &nbsp;</strong> </dt>\n"; 
										$listDisplay .= "                      <dd>&euro; ".$FundDetaildata['fundraiserDetail']['offline_fundraising']."</dd>\n"; 
										$listDisplay .= "                    </dl>\n";
								
								$listDisplay .= '</div>';
							/* Target Details */	
								
							//Running total	
							$listDisplay .= '</div>';
							
							$listDisplay .= '</div>';

							$listDisplay .= '</div>';
							
							/* middle main section */
							$listDisplay .= '<div class="iDonatemain_sections"> ';
							
								$listDisplay .= '<div class="iDonateleftsection">';
								

								if($FundDetaildata['fundraiserDetail']['daysremain'] > 0) {
									$listDisplay .= '<div class="iDonateboxouter" '.$displayEvent.'>';
									$listDisplay .= '<h3 class="left">Event Calendar</h3><div class="iDonateblueborder_left"> </div> <div class="clear"></div>';
									/* Event Calender */	
							    	$listDisplay .= '<div class="iDonateevent_calendar" >';
								  	$listDisplay .= " <div id=\"vm-countdown\" style=\"display: block;\">\n"; 
									$listDisplay .= "                        <div>\n"; 
									$listDisplay .= "                          <div>\n"; 
									$listDisplay .= "                            <div class=\"content\"> <span>Only</span> <strong>\n"; 
									$listDisplay .= "                              ".$FundDetaildata['fundraiserDetail']['daysremain']."\n"; 
									$listDisplay .= "                              </strong> <span>days to go!</span> </div>\n"; 
									$listDisplay .= "                          </div>\n"; 
									$listDisplay .= "                        </div>\n"; 
									$listDisplay .= "                      </div>\n";
								  
								  $listDisplay .= '</div>';
								  $listDisplay .= '</div>';
								}
								/* Event Calender */

									$listDisplay .= '<div class="iDonateboxouter" style="display:block;">';
									$listDisplay .= '<h3 class="left">I\'m Fundraising For</h3><div class="iDonateblueborder_left"> </div> <div class="clear"></div>';
									$listDisplay .= '<div class="iDonatecharity_box">';
											
									if($FundDetaildata['fundraiserDetail']['charitiesdetail'] > 0) {
									
										foreach($FundDetaildata['fundraiserDetail']['charitiesdetail'] as $charities){
												$listDisplay .= '<div class="iDonatecharity_logo_main" >';
													$listDisplay .= '<img src="'.$charities['charity_logo'].'" id="'.$charities['charityname'].'" class="charitylogo" /><br /> <center><strong>'.$charities['charityname'].'  </strong></center>';
												$listDisplay .= '</div>';
										}
									
									}
											
											
											$listDisplay .= "<iframe src=\"//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FiDonate.Charity.Fundraising.Ireland&amp;width=200px&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=35&amp;appId=287550318019453\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:100%; height:35px; margin-top: 10px; padding-right:10px;\" allowTransparency=\"true\"></iframe><br />\n"; 
											$listDisplay .= "\n"; 
											$listDisplay .= "        \n"; 
											$listDisplay .= "        <iframe src=\"//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FiDonate.Charity.Fundraising.Ireland&amp;width&amp;layout=button&amp;action=recommend&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=722222404473346\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:100%; height:35px;\" allowTransparency=\"true\"></iframe>\n"; 
											$listDisplay .= "        \n"; 
											$listDisplay .= "<iframe allowtransparency=\"true\" frameborder=\"0\" scrolling=\"no\" src=\"//platform.twitter.com/widgets/follow_button.html?screen_name=iDonate_ie&show_count=false\"style=\"width:100%; height:35px;\"></iframe> \n"; 
											$listDisplay .= "                    <div class=\"clear\"></div>\n";
											
											
											$listDisplay .= '</div>';

									$listDisplay .= '</div>';	

									$listDisplay .= '<div class="iDonateboxouter" '.$displayShare.'>';
									
									$listDisplay .= '<h3 class="left">Share My Page</h3><div class="iDonateblueborder_left"> </div> <div class="clear"></div>';		
							
										$listDisplay .= '<div class="iDonateshare_box" style="display:block;"> ';
											$listDisplay .= '<h4 >My Page&nbsp;:&nbsp;<a href="'.$FundDetaildata['fundraiserDetail']['userurllink'].'" style="text-decoration:none;" target="_blank">'.$FundDetaildata['fundraiserDetail']['userurl'].'</a></h4>';
											
											$listDisplay .= "<a class=\"a2a_dd\" href=\"http://www.addtoany.com/share_save\"><img src='".$FundDetaildata['fundraiserDetail']['userurlimg']."' width=\"171\" height=\"16\" border=\"0\" alt=\"Share\"/></a>\n";
											
											$listDisplay .= '<script type="text/javascript" src="'.iDonateD_DOMAIN.'/javascript/page.js"></script>';
		
											
										$listDisplay .= '</div>';	
										
									$listDisplay .= '</div>';
												
												
								 $listDisplay .= '<div class="iDonateboxouter" '.$displayGallery.'>
								  <h3 class="left">My Photos</h3>
								  <div class="iDonateblueborder_left">&nbsp;</div>
								  <div class="clear"></div>	';
								  
								  $countImages = count($FundDetaildata['fundraiserDetail']['fundraiser_images']);
								  
								  if($countImages > 0 && is_array($FundDetaildata['fundraiserDetail']['fundraiser_images'])) {
								  
									  $listDisplay .= '<ul class="iDonatenodot">';
										
										foreach($FundDetaildata['fundraiserDetail']['fundraiser_images'] as $fundImages) {
										
										$listDisplay .= '<li>';
										
										$listDisplay .= '<a href="'.str_replace('thumb/','',$fundImages).'" data-simplbox class="pirobox_gall"> <img height="48" width="48" src="'.$fundImages.'" alt="Fundraiser Images"/> </a>';
											
										$listDisplay .= '</li>';
										
										}
										
									  $listDisplay .= '</ul>';
								  
								  } else {
										$listDisplay .= '<img src="'.$FundDetaildata['fundraiserDetail']['fundraiser_images'].'" alt="No Images" title="No Images"/>';
								  }
								
								$listDisplay .= '</div>';
								
								$listDisplay .= "<div class=\"iDonateboxouter\" ".$displayVideo.">\n"; 
									$listDisplay .= "<h3 class=\"left\">My Video</h3>\n"; 
									$listDisplay .= "<div class=\"iDonateblueborder_left\">&nbsp;</div>\n"; 
									$listDisplay .= "<div class=\"clear\"></div>\n"; 
									
										if($FundDetaildata['fundraiserDetail']['video_link'] != '') {
										$vidlen = strlen($FundDetaildata['fundraiserDetail']['video_link']);
										if($vidlen < 80) {
											$listDisplay .= '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.html_entity_decode($FundDetaildata['fundraiserDetail']['video_link']).'" frameborder="0" allowfullscreen></iframe>'; 
											} else {
											$listDisplay .= html_entity_decode($FundDetaildata['fundraiserDetail']['video_link']);
											}
										} else { $listDisplay .= "Video is Not Available"; }
								
									$listDisplay .= "</div>\n";

								$listDisplay .= "<div class=\"iDonateboxouter\" ".$displayTwitter.">\n"; 
								$listDisplay .= "<h3 class=\"left\">Join My Network</h3>\n"; 
								$listDisplay .= "<div class=\"iDonateblueborder_left\">&nbsp;</div>\n"; 
								$listDisplay .= "<div class=\"clear\"></div>\n"; 
								$listDisplay .= "\n";

								$listDisplay .= "<div class=\"twitter_box\">\n"; 
								$listDisplay .= "<div class=\"twitter_feed\">\n"; 
								$listDisplay .= "<a class=\"twitter-timeline\" width=\"280\" height=\"300\"  href=\"https://twitter.com/".$FundDetaildata['fundraiserDetail']['twitter_link']."\"\n"; 
								$listDisplay .= "data-widget-id=\"430715448401743873\"\n"; 
								$listDisplay .= "data-screen-name=\"".$FundDetaildata['fundraiserDetail']['twitter_link']."\" data-show-replies=\"false\"\n"; 
								$listDisplay .= ">Tweets by @".$FundDetaildata['fundraiserDetail']['twitter_link']."</a>\n"; 
								$listDisplay .= "\n"; 
								$listDisplay .= "\n"; 
								$listDisplay .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\"://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>\n"; 
								$listDisplay .= "</div>\n"; 
								$listDisplay .= "</div>\n";
								
								$listDisplay .= "</div>";
								
								$listDisplay .= '</div>';
								
							$listDisplay .= '<div class="iDonaterightsection">';
							
								$listDisplay .= "<div class=\"iDonateboxouter\">\n"; 
								$listDisplay .= "<h3 class=\"left\">More Info....</h3>\n"; 
								$listDisplay .= "<div class=\"iDonateblueborder_right\">&nbsp;</div>\n"; 
								$listDisplay .= "<div class=\"clear\"></div>\n"; 
								$listDisplay .= "<div class=\"iDonateabout_text\"> ".html_entity_decode($FundDetaildata['fundraiserDetail']['description'])." </div>\n"; 
								$listDisplay .= "<div class=\"iDonateblog_info\" ".$displayBlog.">\n"; 
								$listDisplay .= "<h4>\n"; 
								$listDisplay .= "\n";
								
								if($FundDetaildata['fundraiserDetail']['blog_link'] != "") {
								
									$listDisplay .= '<a target="_blank" style="text-decoration:none;" href="'.$FundDetaildata['fundraiserDetail']['blog_link'].'">'."Visit My Blog"."</a>";
								
								} else {
									$listDisplay .= "Visit My Blog";		
								}
								
								$listDisplay .= "</h4>\n"; 
								
								$listDisplay .= '</div>';
								
							$listDisplay .= '</div>';
							
								$listDisplay .=  "<div class=\"iDonateboxouter\">\n"; 
								$listDisplay .=  "<h3 class=\"left\">Comments <strong></strong></h3>\n"; 
								$listDisplay .=  "<div class=\"iDonateblueborder_right\">&nbsp;</div>\n"; 
								$listDisplay .=  "<div class=\"clear\"></div>\n"; 
								$listDisplay .=  "<div class=\"comments_box\"><div class=\"fb-comments\" data-href=".$FundDetaildata['fundraiserDetail']['page_link']."  data-width=\"590\" data-num-posts=\"5\"></div> </div>\n"; 
								$listDisplay .=  "\n"; 
								$listDisplay .=  "<!-- end blog_info -->\n"; 
								$listDisplay .=  "</div>\n";
												 
								$listDisplay .=  "<div class=\"iDonateboxouter\">\n"; 
								$listDisplay .=  "<h3 class=\"left\">Recent Donors <strong></strong></h3>\n"; 
								$listDisplay .=  "<div class=\"iDonateblueborder_right\">&nbsp;</div>\n"; 
								$listDisplay .=  "<div class=\"clear\"></div>\n"; 
								$listDisplay .=  "<div class=\"iDonaterecent-donors\">\n"; 
								if($FundDetaildata['fundraiserDetail']['recentDonors'] > 0) {
								$listDisplay .=  "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"iDonatedonors iDonateResponsiveTable\">\n"; 									 
								$listDisplay .=  "<tbody><tr>\n"; 
								$listDisplay .=  "<th width=\"25%\" align=\"left\">Name</th>\n"; 
								$listDisplay .=  "<th width=\"15%\" align=\"left\">Amount</th>\n"; 
								$listDisplay .=  "<th width=\"15%\" align=\"center\">Date</th>\n"; 
								$listDisplay .=  "<th width=\"51%\" align=\"center\">Message</th>\n"; 
								$listDisplay .=  "</tr>\n"; 
								
									foreach($FundDetaildata['fundraiserDetail']['recentDonors'] as $recentDonor) {				 
										$listDisplay .=  "<tr>\n"; 
										$listDisplay .=  "<td>&nbsp;\n".$recentDonor['Name']." </td>\n"; 
										$listDisplay .=  "<td>&nbsp;\n".$recentDonor['Amount']."</td>\n"; 
										$listDisplay .=  "<td align=\"center\">".$recentDonor['Date']."</td>\n"; 
										$listDisplay .=  "<td align=\"center\">".$recentDonor['tMessage']."</span>\n</td>\n"; 
										$listDisplay .=  "</tr>\n"; 
									}
								
								$listDisplay .=  "</tbody></table>\n"; 
								} else {
								$listDisplay .= 'No data found';
								}
								$listDisplay .=  "</div>\n";

								$listDisplay .=  "</div>\n";

								$listDisplay .=  "<div class=\"iDonateboxouter\">\n"; 
								$listDisplay .=  "<h3 class=\"left\">I'm Fundraising For <strong></strong></h3>\n"; 
								$listDisplay .=  "<div class=\"iDonateblueborder_right\">&nbsp;</div>\n"; 
								$listDisplay .=  "<div class=\"clear\"></div>\n";
								$listDisplay .=  "<div class=\"iDonatecharity_info\">";
								
								if($FundDetaildata['fundraiserDetail']['charitiesdetail'] > 0) {
								
									foreach($FundDetaildata['fundraiserDetail']['charitiesdetail'] as $charitiesDetails) {				 
									
										$listDisplay .=  "<div class=\"iDonatecharity_main\">";
											$listDisplay .=  "<div class=\"iDonatecharity_image\">";
												$listDisplay .=  "<img src=\"".$charitiesDetails['charity_logo']."\" alt=\"charity images\" />";
											$listDisplay .=  "</div>";
										$listDisplay .=  "<div class=\"iDonatecharity_name\"> <font size=\"3\"><strong>".$charitiesDetails['charityname']."</strong></font> </div>";								$listDisplay .=  "<div class=\"iDonatecharity_desc\"> ".$charitiesDetails['charity_description']." </div>";
										$listDisplay .=  "<a target=\"_blank\" href=\"".$charitiesDetails['charity_link']."\" class=\"iDonatelinkArw\">Read More About <b>".$charitiesDetails['charityname']."</b></a>";
										$listDisplay .=  "</div>";
									
									}
								
								}
								
								$listDisplay .=  "</div>";
								
								$listDisplay .=  "</div>\n";
								
								$listDisplay .=  "</div>\n";
							/* middle main section */
						
						$listDisplay .= '</div>';
						
					//<div class="profile_info">
					$listDisplay .= '</div>';
					//mainintro
					
					
				
					$listDisplay .= '</div>';
				
				$listDisplay .= '</div>';
			
			$listDisplay .= '</div>';
		
		
		$listDisplay .= '</div>';

	/* For fundraiser Detail page */
	
	} else {
	/* For fundraiser list page */

	
	if($iDonateD_active != '') {
	$FundListdata = file_get_contents(iDonateD_DOMAIN."api/getFundraiserList?charityid=".$iDonateD_chid);
	$FundListdata = json_decode($FundListdata, true); // Turns it into an array, change the last argument to false to make it an object
	
	$listDisplay = '';
	
		$listDisplay .= '<div id="iDonateMain" class="col-xs-12 col-md-12 col-sm-12">';
		
		$listDisplay .= '<div class="iDonateHeader">';
		
		$listDisplay .= '<h2 class="iDonateH2">';
		
		$listDisplay .= html_entity_decode($FundListdata['charityName']).' Fundraisers List';
		
		$listDisplay .= '</h2>';
		
		$listDisplay .= '</div>';
		
		
			$listDisplay .= '<div class="FundraiserList display" cellspacing="0" width="100%">';
				
				$listDisplay .= '<table id="idonateTable" border="0" class="iDonateResponsiveTable stripe" width="100%">';
				
					$listDisplay .= '<thead>';
						
						$listDisplay .= '<tr>';
					
						$listDisplay .= '<th>';
						
							$listDisplay .= 'Page Title';
						
						$listDisplay .= '</th>';

						$listDisplay .= '<th>';
						
							$listDisplay .= 'Fundraiser Name';
						
						$listDisplay .= '</th>';

						$listDisplay .= '<th>';
						
							$listDisplay .= 'Page Link';
						
						$listDisplay .= '</th>';
						
						$listDisplay .= '</tr>';
					
					$listDisplay .= '</thead>';			

					$listDisplay .= '<tbody>';			

		if($FundListdata != '') {
				
				foreach($FundListdata['fundraisers'] as $fundList) {

					$listDisplay .= '<tr>';			
					
					$listDisplay .= '<td class="iDonateFundName">';
					
					$file_headers = @get_headers($fundList['profile_picture']);
					if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
						$fundList['profile_picture'] = iDonateD_DOMAIN.'images/no-image-avaliable.gif';
					}
					
					$listDisplay .=  '<img src="'.$fundList['profile_picture'].'" width="40" class="iDonateFundListImg"> '.$fundList['pagetitle'];
					$listDisplay .= '</td>';
					
					$listDisplay .= '<td>';
					$listDisplay .=  $fundList['name'];
					$listDisplay .= '</td>';
					
					$listDisplay .= '<td>';
					$listDisplay .=  '<a href="?FundPageid='.base64_encode(base64_encode($fundList['fundraiser_id'].'!!'.'idonateie')).'">View</a>';
					$listDisplay .= '</td>';

					$listDisplay .= '</tr>';			
				
				}

		} else {

					$listDisplay .= '<tr>';			
					
					$listDisplay .= '<td colspan="3" align="center">';
					$listDisplay .=  'No records found';
					$listDisplay .= '</td>';
					$listDisplay .= '</tr>';			

		}

					$listDisplay .= '</tbody>';			
	
				$listDisplay .= '</table>';

			$listDisplay .= '</div>';
			
		$listDisplay .= '</div>';

	} else {
	$FundListdata = '';
	iDonateD_remove_my_shortcode( $tag );
	}

	/* For fundraiser List page */

	
	} 
	
	
	} else {
		$listDisplay = '<div class="noiDonateInternet" id="iDonateMain">iDonate Donation plugin could not work because of no internet connection.</div>';
	}
	
	if($iDonateD_active == 'active') {
		$returnData = $listDisplay;
	} else {
		$returnData = "[getFundraiserList]";
	}
	
	return $returnData;

}

add_shortcode( 'getFundraiserList' , iDonateD_getFundList );


?>
