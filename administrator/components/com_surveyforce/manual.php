<?php
/**
* Survey Force component for Joomla
* @version $Id: manual.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage manual.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_VALID_MOS' ) or die( 'Restricted access' );
global $mosConfig_live_site;
?>
<div style="text-align:left; padding:5px; font-family: verdana, arial, sans-serif; font-size: 9pt;">

<h3>1. <b>COMPONENT MAIN MENU</b></h3>
<div style="padding-left:10px ">
<h3><b>1.1 Categories</b></h3>
<div style="padding-left:10px ">
<p>In that section you can create categories for surveys. Categories are used to provide convenient navigation through Surveys.</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>1.2 Surveys</b></h3>
<div style="padding-left:10px ">
<p>
In that section you can create, edit and publish the surveys. To create new survey press `<em>New</em>` button and input the following parameters (on completion press '<em>Save</em>' button):<br>
&nbsp;-&nbsp;&nbsp;&lsquo;Name&rsquo; - &nbsp;iInput  survey name;<br>
&nbsp;-&nbsp;&nbsp;&lsquo;Description&rsquo; -&nbsp;&nbsp;input  survey description;<br/>
&nbsp;-&nbsp;&nbsp;&lsquo;Short description&rsquo;&nbsp;- &nbsp;input  survey short description, it will be shown only in the category view<br>
&nbsp;-&nbsp;&nbsp;&lsquo;Enable Welcome Screen (Description)&rsquo;&nbsp;- &nbsp;select the option if you'd like a survey description to show before the survey start<br>
  &nbsp;-&nbsp;&nbsp;&lsquo;Image&rsquo; - choose background picture for the  survey (optional).Background picture is shown only if questions in the survey are displayed one per one page.<br>
  &nbsp;-&nbsp;&nbsp;&lsquo;Show Progress Bar&rsquo; - select the option if you&rsquo;d  like the progress bar to be shown.<br/>
    &nbsp;-&nbsp;&nbsp;&lsquo;Template&rsquo; - choose template;<br/>
  &nbsp;-&nbsp;&nbsp;&lsquo;Category&rsquo; - choose category;<br>
  &nbsp;-&nbsp;&nbsp;&lsquo;Expired on&rsquo; - input survey expiry date (optional);<br>
    &nbsp;-&nbsp;&nbsp;&lsquo;Active&rsquo; - choose the survey state -  published(active) or unpublished;<br/>
  &nbsp;-&nbsp;&nbsp;&lsquo;Randomize questions&rsquo; &ndash; select whether to randomize the questions or not. Neither of the question rules nor `Don't show this  question if` options will work if the option 'Randomize questions' is enabled.<br/>
  &nbsp;-&nbsp;&nbsp;&lsquo;Auto insert Page Breaks&rsquo; - If `<em>Yes</em>`, then all Page Breaks will be ignored and the questions will be displayed on the page one per one page, if `<em>No</em>` then the questions will be displayed according to Page Breaks arrangement.<br/>
 &nbsp;-&nbsp;&nbsp;&lsquo;Do not store personal information about registered users&rsquo; &ndash; select the option if you&rsquo;d  like the information about the registered users, who have passed the survey, to  be saved.<br/>
  &nbsp;-&nbsp;&nbsp;Assign user access rights for survey. There are 4 user types: Guests, Registered users, Invited users, users from the selected User`s Lists Lists and for Friends (the option is worthwhile if Jomsocial is used).<br>
  <br/>
  For unregistered users you can  choose ip and/or cookies control to prevent from passing the survey over and  over again. Then you can set how many times an unregistered user can take the  survey (voting option - same for registered and invited users, see below).<br/>
  <br/>
  For invited and registered users you can select voting options:
  &nbsp;-&nbsp;&nbsp;`Multiple voting` -  - user can take survey many times and all results will be stored.<br>
  &nbsp;-&nbsp;&nbsp;`Single voting` - user can take survey once  and only one result will be stored.<br>
  &nbsp;-&nbsp;&nbsp;`Single voting - replace answers` - user can take survey many times, but only one result (latest) will be stored.<br>
  &nbsp;-&nbsp;&nbsp;`Single voting - edit answers` - user can take survey many times, but only one result (latest) will be stored and user will see his previuos answer so that he can change it.<br>
  <br/>
  If survey is '<em>unpublished</em>' it cannot be accessed by users. To publish survey press '<em>Publish</em>' button, to unpublish press '<em>Unpublish</em>' button.<br>
  <br>
  To edit survey parameters press '<em>Edit</em>' button.<br>
  To delete survey (the questions aren't deleted) press '<em>Delete</em>' button. To delete the residuary questions for that survey you should delete them manually on Questions page.<br>
  '<em>Move</em>' button allows to transfer surveys (or list of surveys) to another category.<br>
  '<em>Copy</em>' button allows to copy surveys (or list of surveys) including all questions.<br>
  '<em>Preview</em>' button allows to preview the survey regardless of whether the survey is published or not, whether it has expired or not. The preview procedure is completely similar to taking a survey: you need to answer to questions, the rules you defined work according to your answers, Prev-Next navigation works as well (the only difference is that if the survey ends with the graphic results, then admin answers are not counted).<br/>
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>1.3 Questions</b></h3>
<div style="padding-left:10px ">
<p>
If you select survey on surveys page and follow the  link there will appear a page with a list of questions for present survey. There're 8 types of questions:<br>
<div style="padding-left:10px "></div>
<b>Likert Scale</b><br><div style="padding-left:10px ">
When you want to know respondents' feelings or attitudes about something, consider asking a Likert-scale question. The respondents must indicate how closely their feelings match the statement on a rating scale.<br>
</div>
<b>Pick One</b><br>
<div style="padding-left:10px ">
This type of question has two or more options. The student can select only one answer. This question has two styles: using radiobuttons (default) or using a drop-down list.<br/>
</div>
<b>Pick Many</b><br><div style="padding-left:10px ">
When you want respondents to pick the best answer or answers from among all the possible options, consider writing a `Pick Many` question.<br>
</div>
<b>Short Answer</b><br><div style="padding-left:10px ">
The student is required to type in their answer. Responses 3-6 words long are appropriate for short answer questions.<br>
</div>
<b>Drop Down question</b><br><div style="padding-left:10px ">
Two columns of information are displayed. The student must indicate the appropriate matches of information.<br>
</div>
<b>Drag'n'Drop question</b><br><div style="padding-left:10px ">
Two columns of information are displayed. The student must indicate the appropriate matches of information via dragging options from right column to the left.<br>
</div>
<b>Boilerplate</b><br><div style="padding-left:10px ">
This question type is used for displaying some text for explaining or instructions. It is not necessary to answer it.<br>
</div>
<strong>Ranking question</strong><br>
<div style="padding-left:10px ">This type of question is used  to indicate the rank for answer variants.<br>
</div>
<b>Page Break</b><br><div style="padding-left:10px ">
This is the special question type which is used for dividing the survey into pages. All the questions located  between two Page Breaks will be displayed on  one page. If the mode &lsquo;Auto insert Page  Breaks&rsquo; is &lsquo;on&rsquo;, the survey dividing into pages with Page Breaks will be  ignored.<br>
</div>
<br>
To create new questions choose the new question type in the combobox and press the `<em>New</em>` key, and input the following parameters:<br>
&nbsp;-&nbsp;&nbsp;Input question text<br/>
&nbsp;-&nbsp;&nbsp;Select survey for which the question is assigned<br/>
&nbsp;-&nbsp;&nbsp;Select '<em>importance scale</em>' for question<br/>
&nbsp;-&nbsp;&nbsp;Select if answering on this question is necessary.<br/>
&nbsp;-&nbsp;&nbsp;Select if the Page Break will be added after this question.<br/>

&nbsp;-&nbsp;&nbsp;Fill in additional information that characterizes and describes answer variants (for each question type information differs)<br>
&nbsp;-&nbsp;&nbsp;Press '<em>Save</em>' button on completion<br>
<br>
For all question types (except '<em>Short Answer</em>') there are <strong>'<em>question rules</em>'</strong> that allow to adjust questions appearance order. Also depending on answer it can be adjusted so that user will jump over any of the posterior questions. If several rules were activated for one question then changeover will take place for the rule with the highest priority. If  several questions were displayed on the one page and the rules were activated for some of them then changeover will take place for the question`s rule standing first in the succession. The rules can be defined for  the &lsquo;Likert Scale&rsquo; only if you had saved the question.  <br/>
Also you can use an <em>'unconditional rule'</em> "Go to question XY next, regardless of what answer the user selects".
To do this just mark checkbox. Unconditional rule have priority equal 1000, so if you want to have rules that override unconditional rule just set them priority more than 1000 (note that unconditional rule works even if a user did not answer the question)<br/><br />
For '<em>Likert Scale</em>' you can set options and estimation scale. You can use newly created estimation scale or existing one. Also the question has the additional  option &lsquo;Factor name&rsquo;, where you can enter a title for the column which has  different question options. <br>
For '<em>Drop Down</em>' and '<em>Drag and Drop</em>' questions you can set a pair of options which should be associated then.<br>
For &lsquo;<em>Pick One</em>&rsquo; question using  the option &lsquo;<em>Use drop down style</em>&rsquo; you can select the set of radio buttons or one  drop down list to show the answer variants. <br>
<br/>
Also for each question type, except Short Answer you can set default answer (for that you should firstly save the question)<br/><br/>
To edit questions parameters press '<em>Edit</em>' button.<br>
To delete press '<em>Delete</em>' button.<br>
'<em>Copy</em>' button allows you to copy question (or list of questions) to selected survey.<br>
'<em>Surveys</em>' button allows you to return to surveys list.<br/><br/>
To have convenient questions management several questions could be grouped in Sections. You can create the Section by pressing the button `New Section`, further you should enter the following parameters:<br/>
&nbsp;-&nbsp;&nbsp;Section name<br/>
&nbsp;-&nbsp;&nbsp;Select which survey the question will be applied to.<br/>
&nbsp;-&nbsp;&nbsp;Select questions which will belong to the section (or `- No questions -` for the empty section).<br/>
After adding the questions adding to the section their order will be changed according to the section order. All the operations over the section - copying, moving, deleting will affect to the questions in this section.<br/>

</p>
</div>
</div>


<div style="padding-left:10px ">
<h3><b>1.4 Manage Users</b></h3>
<div style="padding-left:10px ">
<p>
On that page you can see the list of users. To create a new list press '<em>New</em>' button and input the following information:<br>
&nbsp;-&nbsp;&nbsp;input list name;<br>
&nbsp;-&nbsp;&nbsp;select survey for which you create that list of users;<br>
&nbsp;-&nbsp;&nbsp;select necessary parameters for users adding:<br>
&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;'<em>add registered users</em>' adds all registered Joomla users to the list;<br>
&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;'<em>import from csv</em>' (users are added from the specified csv-file;<br>
&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;'<em>add users manually</em>' adds users manually;<br>
&nbsp;-&nbsp;&nbsp;Press '<em>Save</em>' buttons.<br>
<br>
When adding users from CSV-file the file should have the following structure:<br>
lastname,name,email<br>
Bush,George,bush@bush-host.tst<br>
You can find the example of CSV file here: <a href="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/example_users.csv">example_users.csv</a><br>
Having created the list of users you can delete (add) users from (to) the list manually.<br>
<br>
Also you can invite users from certain list to pass one of surveys. To invite user press '<em>Invite</em>' button, then choose '<em>e-mail message</em>' from the list in appeared window and press '<em>Start</em>'.<br>
As a result each user will receive invitation e-mail message.<br>
'<em>Remind</em>' button allows to notify users who were invited, but haven't followed the survey link in invitation email.<br>
Also list of users could be used to give the opportunity to complete the survey for users from special lists. ( the list can be chosen while creating or editing the survey).<br />
</p>
<strong>Generate Invitations</strong><br/>
<p>If you want to send invitations with links to pass the survey by yourself, then you can use "Generate Invitations" option. You can generate the necessary number of invitations and get them in a CSV-file. To do this you should set the needed number of invitations and select a survey, after that you should press the "Generate" button.
<br/>
To generate invitations users are created automatically (these are not Joomla! Users, they are inner SurveyForce users) who are placed in a special "_generated_users_" group.
<br/>
These users have names like "Name 23" "Lastname 23" "email@email.email". CSV-file will contain the following info - name, lastname and email of the generated user, and the link to pass the survey.
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>1.5 Manage e-mails</b></h3>
<div style="padding-left:10px ">
<p>
In that section you can create invitation e-mail messages to be sent to users. To write proper message text use <em>#link#</em> and <em>#name#</em> constants which will be replaced to username and personal link (leading to survey page) upon sending. 
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>1.6 Importance scales</b></h3>
<div style="padding-left:10px ">
<p>
On that page you can assign '<em>importance scales</em>' which can later be set up for questions. '<em>Importance scales</em>' is used to ascertain user's opinion on the question. When you create 'importance scale' you can adjust question values and text that offers user to let you assess question.
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>1.7 Configuration</b></h3>
<div style="padding-left:10px ">
<p>
In that section you can set component global parameters, like:<br/>
&nbsp;-&nbsp;&nbsp;Colours of dragged rectangles while for 'Drag and Drop' questions and colours of 'Progress Bar';<br/>
&nbsp;-&nbsp;&nbsp;Component version preview.<br/>
&nbsp;-&nbsp;&nbsp;Configure type and size  of the diagrams for the survey results displaying.<br/>
&nbsp;-&nbsp;&nbsp;Configure mail settings.<br/>
&nbsp;-&nbsp;&nbsp;Set up email notifications. When a user passes the survey his results are sent to survey`s author and/or other users.</p>
<p>
<strong>1.7.1 JoomlaLMS Integration</strong><br />
You have an ability to integrate the component with JoomlaLMS. The integration includes:<br />
<ol>
 <li>Users who are assigned as teachers in LMS courses automatically become survey authors.</li>
 <li>You can use usergroups that you create in SurveyForce in JoomlaLMS as well.</li>
</ol>
To enable the integration:<br />
Go to Components>SurveyForce>Configuration>Basic tab and check the option JoomlaLMS Integration<br />
<br />
Note: If you want to use surveys inside the courses you need to install and publish the content plugin that comes with the component, and insert tags for surveys e.g. your learning path content will include the tag {surveyforce id=1} where 1 is the id of the survey.
</p>
<p>
<strong>1.7.2 JomSocial Integration</strong><br />
You have an ability to integrate surveys into JomSocial. This option works only in case you have installed and published the plugin for JomSocial that can be added to the SurveyForce subscription.<br />
To enable the integration:<br />
<ol>
	<li>Install and publish the JomSocial plugin</li>
	<li>Go to Components>SurveyForce>Configuration>Basic tab and check the option JomSocial Integration</li>
</ol>
Once the integration is enabled all registered users on your site become survey authors (the manage authors tab becomes not valid).<br />
Then each user can find survey management in his profile tab on the front-end. <br />
A registered user can add, modify and publish survey on his profile (and choose to make them public, for registered users, or available for his friends only). He can also view the reports on the front-end and delete surveys.<br />
If the survey is published and available it can be viewed on user's profile.<br />
<br />
Registered users will see the simplified front-end survey management.<br />
Users who are also added to  survey Authors will see the usual front-end management version (not simplified).
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>1.8 Templates</b></h3>
<div style="padding-left:10px ">
<p>
In this section you can upload a new template or edit the template CSS file. To create a new template you should download a folder with the template form `media` folder (or you can get it from the package with `Survey Force Deluxe` component). Then you should change the name of the template in `template.xml` file to the desired. Then edit `template.php` and `surveyforce.css` files to create a new template (if you add some images in template's folder `images` you should add description of this images in `template.xml` file, see `template.xml` file of `surveyforce_new` template for example). After you made changes you want you should create a zip-package and install it.<br/>
Another  way to change the way the survey looks is to edit CSS and PHP files of one of the standard templates.

</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>1.9 Reports</b></h3>
<div style="padding-left:10px ">
<p>
In that section you can review reports on how users were passing surveys. Also on that page you can see all users' attempts while passing the survey (in chronological order).<br>
The following search filters are available: by survey, by user's answers, by user type, by list of users, etc.<br />
<br>
At first there're 3 filters available: <em>by answer status</em>, <em>by survey</em>, <em>by user type</em>.<br />
1. <strong>by answer status</strong>. Available options: completed (survey was passed successfully), not completed (survey started but not finished).<br />
2. <strong>by survey</strong>. Filters by survey name.<br />Upon by survey filter activation <strong>by user's answers</strong> filter becomes available (allows filtering results by selected answers).<br />
3. <strong>by user type</strong>. Available options: Invited users, Registered users, Guests.<br />
Upon Invited users option activation <strong>by list of users</strong> filter becomes available (allows filtering invited users by the list name these users are associated with).<br />
Upon printing (PDF, CSV) ONLY marked results are included into report. If NO results are marked then all filtered results are printed.
By pressing '<em>PDF</em>' button you can print all filtered listings.<br />
Press '<em>CSV(sum)</em>' button to get summarized information on all results in the form of Excel file.<br />

With the help of the buttons '<em>PDF(sum)</em>' and '<em>PDF(sum)</em>'  you can get the total report in numbers and percents.
The report will contain only defined results from the list.<br/>


'<em>Report</em>' button shows information for specifically passed survey.<br>
'<em>Surveys</em>' button shows list of surveys using which you can review and print summary Report.<br>
'<em>UserLists</em>' button shows lists of users using which you can review and print summary Report for list of invited users.<br>
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>1.10 Advanced Reports</b></h3>
<div style="padding-left:10px ">
<p>
There are two types of Advanced Reports: Cross Report and CSV Report. Cross Report is used to get information about answers depending on chosen question`s answer (so-called Column Question). CSV Report is used to get answers grouped in the chart with the following order: as columns are used questions and as rows are used user`s answers.
</p>
</div>
</div>
<div style="padding-left:10px ">
<h3><b>1.11 Manage Authors</b></h3>
<div style="padding-left:10px ">
<p>
Here you can give authors privileges to registered users. User with author rights can create surveys in the front end and also he can create reports for his surveys.
Note: while the integration mode with Joomla LMS is turned on, LMS teachers are automatically given authors privileges. 
</p>
</div>
</div>

<div style="padding-left:10px ">
<h3><b>Front End</b></h3>
<div style="padding-left:10px ">
<p>
Now authors have opportunity to create surveys in the front end. The survey management and creation system in the front end is absolutely identical that system in the back end. Surveys creation page opens by default (that means on the link 
<a href="<?php echo $mosConfig_live_site?>/index.php?option=com_surveyforce">http://your_site/index.php?option=com_surveyforce</a>) if user has sufficient privileges (he is set as author or LMS teacher while the integration mode is turned on).
</p>
</div>
</div>

<br>
<h3>2. <b>What to do in case you meet problems.</b></h3>
<div style="padding-left:10px ">
<p>
Before making a request to support please try to do the following:<br>
&nbsp;-&nbsp;&nbsp;Make sure that the following file '<em>administrator/components/com_surveyforce/surveyforce.xml</em>' is writable (attribute is '<em>0666</em>');<br>
&nbsp;-&nbsp;&nbsp;Make sure that the following folder '<em>images/surveyforce/</em>' is writable (attribute is '<em>0755</em>');<br>
&nbsp;-&nbsp;&nbsp;Download and install latest component updates from <a target="_blank" href="http://www.joomplace.com">www.joomplace.com</a>;<br>
&nbsp;-&nbsp;&nbsp;If nothing of the above helped please send detailed problem description to support and we'll get back to shortly! 
Also in some cases we need admin-panel and FTP access details to your site to solve the problem. Please be ready to provide that information.
</p>
</div>
</div>