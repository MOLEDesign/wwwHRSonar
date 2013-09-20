<?php
/**
* Survey Force component for Joomla
* @version $Id: manual_faq.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage manual_faq.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_VALID_MOS' ) or die( 'Restricted access' );
?>
<div style="text-align:left; padding:5px; font-family: verdana, arial, sans-serif; font-size: 9pt;">

<h3><b>Below are the answers to frequently asked questions:</b></h3>
<div style="padding-left:10px ">
<h3><b>How do I create new survey?</b></h3>
<div style="padding-left:10px ">
<p>At first create new category for survey. Choose '<em>SurveyForce->Categories</em>' menu item, press '<em>New</em>' button, input category name and description (optional). Press '<em>Save</em>' button. After that click on new category name to view surveys for that category. Next press '<em>New</em>' button to create new survey and do the following:<br>
&nbsp;-&nbsp;&nbsp;input name for future survey;<br>
&nbsp;-&nbsp;&nbsp;input survey description (introductory text to be displayed on first survey page);<br>
&nbsp;-&nbsp;&nbsp;select background picture for survey;<br>
&nbsp;-&nbsp;&nbsp;select category for survey;<br>
&nbsp;-&nbsp;&nbsp;input expiry date for survey;<br>
&nbsp;-&nbsp;&nbsp;set access rights for survey.<br>
</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>How do I set users' access rights for survey?</b></h3>
<div style="padding-left:10px ">
<p>When you create (edit) a survey the following options are available:<br>
&nbsp;-&nbsp;&nbsp;<b>Public</b> - all users have access to survey.<br>
&nbsp;-&nbsp;&nbsp;<b>For Invited Users</b> - only invited users have access to survey.<br>
&nbsp;-&nbsp;&nbsp;<b>For Registered Users</b> - only registered users have access to survey.<br>
&nbsp;-&nbsp;&nbsp;<b>For Users in Lists</b> - only users added to this lists have access to the survey.<br>
</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>I've uploaded new background picture. Why doesn't it appear in background pictures survey list?</b></h3>
<div style="padding-left:10px ">
<p>To upload new picture you should press button next to pick list and upload picture in appeared pop-up window. Then refresh the page where you create (edit) survey so as to refresh pictures list. If you want to download a lot of pictures download them into '<em>images/surveyforce</em>' folder by FTP.
</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>How do I know whether users like survey questions or not?</b></h3>
<div style="padding-left:10px ">
<p>You can set '<em>importance scales</em>' for questions to ascertain user's opinion on the question. To do that go to '<em>SurveyForce -> Importance Scales</em>' page, press '<em>New</em>' button, input question text (e.g. '<em>How important is that question for you?</em>'), set answer variants and press '<em>Save</em>' button. Then edit the question for which you want to set '<em>importance scale</em>' and assign created '<em>importance scale</em>' for that question.</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>How do I offer users to estimate some list using specified scale?</b></h3>
<div style="padding-left:10px ">
<p>'<em>Likert Scale</em>' questions type is used specially for that. Using '<em>Likert Scale</em>' you can offer a user to estimate certain list by specified scale. To create such question you should press '<em>Likert</em>' button on questions page. Here in the input information window for new question you can set options list for estimation and scale as well as standard options.</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>The colours of rectangles in '<em>Drag and Drop</em>' questions don't match my template. How do I change that?</b></h3>
<div style="padding-left:10px ">
<p>The colours for '<em>Drag and Drop</em>' question can be changed on the following page '<em>SurveyForce -> Configuration</em>'.
</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>Why is there no link to my site and survey in invitation e-mails?</b></h3>
<div style="padding-left:10px ">
<p>When you input message text on '<em>SurveyForce -> Manage e-mails</em>' page you should use '<strong><em>#link#</em></strong>' variable that will be automatically replaced to link on survey for invited user.
</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>How do I filter results on '<em>Reports</em>' page by users' answers necessary for me?</b></h3>
<div style="padding-left:10px ">
<p>Filter by users' answers becomes available when you're filtering results by surveys. To view it please select survey from dropdown list. And there you will see list for filtering results by answers.
</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>How do I install new update for the SurveyForce component?</b></h3>
<div style="padding-left:10px ">
<p>Firstly, uninstall your version of component, all data will be kept in your database.<br>
Then install new version of component. New component will upgrade your database structure, if necessary, and all your data will be correctly moved to the new version.
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>I had got the message: "<em>This survey not available now</em>".</b></h3>
<div style="padding-left:10px ">
<p>1. Try to assign Survey ID when you create a survey in the menu (survey # column on the far left of the admin survey list page). Also, check the "expired time" and "active state".<br />
2. To test the surveys just create a link in the menu and try to enter it as a registered user. You should go into Main Menu -> Components -> Survey Force -> Surveys select Survey and press "Edit" button. Choose the future date in "Expired on" line. Also check this settings access: "Public", "For Invited Users" and "For Registered Users". When you create the menu item for Surveys - you should choose the type "Component"... In the future, when you finally make your "real" questionnaire, you will first plan it and map it on paper, and only then will write it using SurveyForce.
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>How I can create a list of surveys? I want to be able to create a list which links to them all available surveys.</b></h3>
<div style="padding-left:10px ">
<p>Create the page for list of surveys like:<br />&nbsp;&nbsp;-&nbsp;survey 1;<br />&nbsp;&nbsp;-&nbsp;survey 2;<br />Insert links for survey's names by using next format: <i>index.php?option=com_surveyforce&survey=5</i>. Where '5' is a survey's ID. You can see that in Back-end location string during hover mouse on the survey.
</p>
</div>
</div>


<div style="padding-left:10px ">
<h3><b>How to send invitations?</b></h3>
<div style="padding-left:10px ">
<p>To send invitations go to the Menu 'Components' -> 'Manage Users' select list of users and press button 'Invite' -> but before,  you must create an email text  on 'Manage E-mails' page . This text is the text that you send to users.
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>I need to know if SurveyForce can send out reminders if a person has not fully completed the survey.</b></h3>
<div style="padding-left:10px ">
<p>Only Administrator can send reminders (manually choosing list of users) to users which were invited, but not started the survey.
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>How I can turn off the "How important is this question to you?"</b></h3>
<div style="padding-left:10px ">
<p>You should choose the "Select Imp. Scale" value in "Importance scale" row for all questions of survey.
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>I was wondering how the question rules work?</b></h3>
<div style="padding-left:10px ">
<p>Question rules work for any questions (except Short Answer). If several rules were activated for one question then changeover will take place for the rule with the highest priority. If on the one page several questions were displayed and for some of them rules were activated then changeover will take place for the question's rule standing first in the succession. For the 'Likert Scale' rules can be defined only if you had saved the question.  
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>In SurveyForce's admin area, will I be able to review the results of individual participants? Or will I only be able to review the aggregated results of all participants?</b></h3>
<div style="padding-left:10px ">
<p>Both of them.
</p>
</div>
</div>


<div style="padding-left:10px ">
<h3><b>Is there any limit on how many surveys being active at the same time?</b></h3>
<div style="padding-left:10px ">
<p>No limit for number of active surveys.
</p>
</div>
</div>



<div style="padding-left:10px ">
<h3><b>How I can remove the "SurveyForce. Powered by JoomPlace" line?</b></h3>
<div style="padding-left:10px ">
<p>You need to buy a product "Branding Free" in JoomPlace webshop and then in your members place you need to activate it on any license.
</p>
</div>
</div>



</div>