<html>
  <?php
include('include/header.php');
$CurrentDate = date("Y-m-d");
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

$startTime = $_GET["startTime"];
if($startTime=="") $startTime = "05:00:00";
$endTime = $_GET["endTime"];
if($endTime=="") $endTime = "19:00:00";
$searchWeek = $_GET["searchWeek"];
if($searchWeek=="") $searchWeek = "";

$fromDate = $_GET["fromDate"];
$toDate = $_GET["toDate"];

if ($admin <> 1) { ?>
  <script> window.location.replace("index.php?#"); </script>
<?php die; } ?>

<link href='dist/css/main.css' rel='stylesheet' />

<script src="bower_components/daypilot/helpers/jquery-1.12.2.min.js" type="text/javascript"></script>
<script src="dist/js/main.js"></script>

<link href='dist/css/dragDrop.css' rel='stylesheet' />


<link rel="stylesheet" href="bower_components/calendar/bootstrap-datetimepicker.min.css"/>


<script src="bower_components/calendar/moment.min.js"></script>
<script src="bower_components/calendar/bootstrap-datetimepicker.min.js"></script>
<script src="bower_components/calendar/moment.js"></script>


<style>
  .dropBoxGreen{
    width:65px;
    height:23px;
    background-color:#1A821A;
  }

  .dropBoxRed{
    width:65px;
    height:23px;
    background-color:#C40000;
  }

  .dropBoxOrange{
    width:65px;
    height:23px;
    background-color:#FF8000;
  }

  /* width */
  ::-webkit-scrollbar {
    width: 5px;

  }
  
  /* Track */
  ::-webkit-scrollbar-track {
    box-shadow: inset 0 0 0 darkgrey; 
    border-radius: 10px;
  }
   
  /* Handle */
  ::-webkit-scrollbar-thumb {
    background:white; 
    border-radius: 10px;
  }
  
  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: grey; 
  }

  body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  p {
    text-align: center;
  }

  #calJob{
    display: table;
    
  }

  #calendar {
    width: 80%;
    margin: 50px 5px;
    float:left;
    
  }

  

  #calendar-container{
    position:relative;
    z-index:1;
  }



  #jobSidebar{
    width: 18%;
    margin: 50px 5px;
    background-color:lightgrey;
    float:left;
    display: table-cell;
    
  }

  

  .status{
    float:left;
    margin:10px;
  }
  
  .card-title{
    color:white;
    font-weight:bold;
    margin:5px;
    padding:10px;
  }

  .fc-datagrid-body td {
    cursor: pointer;
  }


  .hidden-event{
    
    color:white;
  }

  .jobCate{
    background-color: white;
    width:80%;
    color: black;
    padding:5px;
    margin:auto;
    font-weight:bold;
    font-size:15px;
  }

  .jobDes{
    background-color:white;
    color:black;
    width:80%;
    margin:auto;
  }
  .siteName{
    padding:10px;
    color:black;
    width:80%;
    margin:auto;
  }
  .clientName{
    background-color:white;
    color:blue;
    width:80%;
    margin:auto;
  }

  .createdDate{
    margin:10px 0;
  }

  .kpiTarget{
    margin:10px 0;
  }

  #external-events{
    z-index:2;
    overflow-y: auto; 
    max-height:700px;
    width:100%;
    
  }
  .jobCard{
    width:100%;
    text-align:center;
    margin:0 0 10px 0;
  }

  .fc-event{
    margin: 10px auto;
  }

  .fc-event-main{
    cursor: move;
    text-align: center;
    padding:10px;
  }

  .fc-event:hover {
    background:color:red;
  }

  input[type='radio']:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #d1d3d1;
        
        
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }

    input[type='radio']:checked:after {
        width: 15px;
        height: 15px; 
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        
        background-color: #ffa500;
        
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }
  
  @media screen and (max-width: 1700px) {
   #external-events {
      max-height:1000px;
   }
}
  
  
</style>

<?php
function eventGroups()  {
    include('include/config.php');
    
    echo '[';   
    $group ='';
      $group .= '{ id: "G1", title: "Mechanical", expanded: true, children:[';
          $users_qry = "SELECT * FROM users WHERE tenantGuid = '$tenantGuid' AND employed = 1 AND active = 1 AND mechanicalStaff = 1 ORDER BY firstname ASC";
          $users_query = mysqli_query($mysqli, $users_qry);
          while ($row = mysqli_fetch_array($users_query)) {
              $userId = $row['idUsers'];
              $firstname = $row['firstname'];
              $surname = $row['surname'];
              $group .= '{ title: "'.$firstname.' '.$surname.'", id: "'.$userId.'"},';
          }
          $group = rtrim($group, ',');      
      $group .= ']},';

      $group .= '{ title: "Electrical", id: "G2", expanded: true, children:[';
          $users_qry = "SELECT * FROM users WHERE tenantGuid = '$tenantGuid' AND employed = 1 AND active = 1 AND electricalStaff = 1 ORDER BY firstname ASC";
          $users_query = mysqli_query($mysqli, $users_qry);
          while ($row = mysqli_fetch_array($users_query)) {
              $userId = $row['idUsers'];
              $firstname = $row['firstname'];
              $surname = $row['surname'];
              $group .= '{ title: "'.$firstname.' '.$surname.'", id: "'.$userId.'"},';
          }
          $group = rtrim($group, ',');
      $group .= ']},';

      $group .= '{ title: "Administration", id: "G3", expanded: true, children:[';
          $users_qry = "SELECT * FROM users WHERE tenantGuid = '$tenantGuid' AND employed = 1 AND active = 1 AND admin = 1 ORDER BY firstname ASC";
          $users_query = mysqli_query($mysqli, $users_qry);
          while ($row = mysqli_fetch_array($users_query)) {
              $userId = $row['idUsers'];
              $firstname = $row['firstname'];
              $surname = $row['surname'];
              $group .= '{ title: "'.$firstname.' '.$surname.'", id: "'.$userId.'"},';
          }
          $group = rtrim($group, ',');    
      $group .= ']},';
    
    $group = rtrim($group, ',');
    echo $group;
    echo ']';
}

?>

<script type="text/javascript">
  //Dragging feature
  document.addEventListener('DOMContentLoaded', function() {
        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendar.Draggable;
      
        var containerEl = document.getElementById('external-events');
        var calendarEl = document.getElementById('calendar');
        

        // initialize the external events
        // -----------------------------------------------------------------
        
        new Draggable(containerEl, {
          
          itemSelector: '.fc-event', //Take data from fc-event's data-target
          
          eventData:{
            title: 'Drop Me', //the title when drop
            stick: false, //stick on the calendar to false
            create: false //create a clone false
          }         
          
        });
      });
</script>
    

<script type="text/javascript">
  //Show hidden card details on click button
  function showMore(obj) {
    var jobguid = obj.value;

    var x = document.getElementById("collapseElement" + jobguid);
    
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
    
  }
</script>

<script type="text/javascript">
  //Show hidden filter box on click button
  function showFilter(obj) {
    var x = document.getElementById("filterbox");
    
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
    
  }
</script>


<script type="text/javascript">
  //Reset filter form
  function resetForm() {
    document.getElementById("searchform").reset();
  }
</script>

<script type="text/javascript">
  //Refresh cards when click button
  function refreshCards() {
    var divClone = $("#external-events").clone(); // Do this on $(document).ready(function() { ... })

    console.log(divClone);
    // Use this command if you want to keep divClone as a copy of "#some_div"
    $("#external-events").replaceWith(divClone.clone()); // Restore element with a copy of divClone

    // Any changes to "#some_div" after this point will affect the value of divClone
    $("#external-events").replaceWith(divClone); // Restore element with divClone itself
  }
</script>

<script>

      
      function searchcard() {
        var loading = '<center><div name="loading" id="loading"><img src="img/loading.gif" height="80px" width="80px"></img></div></center>';
        document.getElementById("external-events").innerHTML = loading;
        $.ajax({
          url: "card_show.php",
          type: "POST",
          data: $('#searchform').serialize(),
          success: function (data) {
            // var result = $('<div />').append(data).find('#external-events').html();
            // console.log(data);
            document.getElementById("external-events").innerHTML  = data;
          },
          error: function (xhr, status) {
              alert("Sorry, there was a problem!");
          },
          complete: function (xhr, status) {
              //$('#showresults').slideDown('slow')
          }
          
        });
      }
</script>

<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      //now: '2020-06-07',
      now: '<?php echo $CurrentDate; ?>',
      editable: true,
      aspectRatio: 1.8,
      scrollTime: '00:00',
      timeFormat: 'H(:mm)',
      headerToolbar: {
        left: 'today prev,next',
        center: 'title',
        right: 'resourceTimelineDay,timeGridWeek,dayGridMonth'
      },
      initialView: 'resourceTimelineDay',
      slotMinTime: '<?php echo $startTime; ?>',
      slotMaxTime: '<?php echo $endTime; ?>',
      views: {
        timeGridWeek: {
          type: 'timeGrid',
          duration: { weeks: <?php if($searchWeek=="") { echo 1; } else{echo $searchWeek;} ?> }
        }
      },

      //initialResources: <?php eventGroups(); ?>,
      resources: <?php eventGroups(); ?>,
      events: function(fetchInfo, successCallback, failureCallback) {
        var startStr = fetchInfo.startStr;
        var endStr = fetchInfo.endStr;
        var timeZone = fetchInfo.timeZone;

        var fromDate = "<?php echo $fromDate; ?>";
        var toDate = "<?php echo $toDate; ?>";
        $.ajax({
          url: "ajaxRefresh.php",
          type: "POST",
          data: {startStr:startStr,endStr:endStr,timeZone:timeZone,fromDate:fromDate,toDate:toDate},
          dataType: "json",
          success: function(response){
            successCallback(response);
            // var events = [];
            // $.each(response, function (i, item) {
            //     events.push({
            //       id: response[i].id,
            //       start: response[i].start,
            //       end: response[i].end,
            //       title: response[i].title,
            //       resourceId: response[i].resourceId
            //     });
            // });
            // successCallback(events);
          }
        });
      },

      
      /*---------------------------------------------------------------*/

      dateClick: function(info) {
        //console.log(info);
        // var events = calendar.clientEvents();
        // console.log(events);
        //console.log($("#frmEvent").serialize());
        $("#frmEvent").trigger("reset");
        var isNumber = parseInt(info.resource.id);
        //console.log(isNumber);
        if(isNumber){
            document.getElementById("userId").value = info.resource.id;

            //Initialise starttime and endtime
            var starttime = moment(info.dateStr).format('YYYY-MM-DD hh:mm:00 A');
            var endtime = "";

            //Convert time to be displayed correctly within the calendar, via Moment.js.
            var startstr = starttime.toString();
            if (startstr.substr(startstr.length -2) == "AM" && startstr.indexOf('12') > -1){
              endtime = moment(starttime).subtract(11, 'hours').format('YYYY-MM-DD hh:mm:00 A');
            }
            else if (startstr.substr(startstr.length -2) == "AM" || startstr.indexOf('12') > -1){
              endtime = moment(starttime).add(1, 'hours').format('YYYY-MM-DD hh:mm:00 A');
            } else {
              endtime = moment(starttime).add(13, 'hours').format('YYYY-MM-DD hh:mm:00 A');
            }

            // alert(starttime+'**'+endTime);
            //var endtime = moment(info.dateStr).format('YYYY-MM-DD hh:mm:ss A');

            document.getElementById("starttime").value = starttime;
            document.getElementById("endtime").value = endtime;
            $('#addScheduleEntry').modal('show');

            $(document).on('click', '.modal_default_ok', function() {

              var eventtitle = $("#eventtitle").val();
              var jobguid = $("#jobguid").val();
              var starttime = $("#starttime").val();
              var endtime = $("#endtime").val();

              if(eventtitle== "" && jobguid==""){
                alert("Please select Event Name or job title");
                return false;
              }
              if(starttime == endtime){
                alert("Please select different date");
                return false;
              }

              $("#process_loader").fadeIn();
              $.ajax({
                  url: "check_validate_datetime.php",
                  type: "POST",
                  data: $("#frmEvent").serialize(),
                  dataType: 'json',
                  async : false,
                  success: function(response){
                      if(response.result == 'false'){
                        $("#process_loader").fadeOut();
                        $("#error-msg").html(response.msg);
                        return false;
                      }else{
                        $("#process_loader").fadeIn();
                        $.ajax({
                            url: "calendar_event_create.php",
                            type: "POST",
                            data: $("#frmEvent").serialize(),
                            dataType: 'json',
                            async : false,
                            success: function(response){
                                if(response.result == 'success'){
                                  $("#process_loader").fadeOut();
                                  $('#addScheduleEntry').modal('hide');
                                  $("#msg").html('<div class="col-md-12" style="margin-top:30px;width:50%;"><div class="alert alert-success">'+response.msg+'</div></div>');
									                setTimeout(function(){ $("#msg").html(""); }, 1500);
                                  calendar.refetchEvents();

                                }
                            }
                        });
                      }
                  }
              });      
          });
        } 
      },
      
      eventClick: function(info, jsEvent, view) { 
        // var arr =  JSON.stringify(event);
        //console.log('clicked event');
        console.log(info);
        var gif = '<img height="30px" width="30px" src="img/loading.gif"></img>';
        $("#eventHistory").html(gif);
                                
        $("#eventDetail").modal("show");  

        var text = JSON.stringify(info, function (key, value) {
        var eventId = info.event._def.publicId;

          $.ajax({
              url: "get_event_detail.php",
              type: "POST",
              data: {eventId:eventId},
              async : true,
              success: function(response){
                $("#error-msg-update").html("");
                $("#eventHistory").html(response);

                $('#editstarttime').datetimepicker(
                  { format: 'YYYY-MM-DD hh:mm:00 A' }).on('dp.change',function (e) {
                });
                $('#editendtime').datetimepicker(
                  { format: 'YYYY-MM-DD hh:mm:00 A' }).on('dp.change',function (e) {
                });

              }
          });
        });
        
        $(document).on('click', '.updateEvent', function() {
            var starttime = $("#editstarttime").val();
            var endtime = $("#editendtime").val();

            if(starttime == endtime){
              alert("Please select different date");
              return false;
            }

            $("#process_loader").fadeIn();
              $.ajax({
                  url: "check_validate_datetime.php",
                  type: "POST",
                  data: $("#frmEventUpdate").serialize(),
                  dataType: 'json',
                  async : false,
                  success: function(response){
                      if(response.result == 'false'){
                        $("#process_loader").fadeOut();
                        $("#error-msg-update").html(response.msg);
                        return false;
                      }else{
                        $("#process_loader").fadeIn();
                        $.ajax({
                            url: "calendar_event_update.php",
                            type: "POST",
                            data: $("#frmEventUpdate").serialize(),
                            dataType: 'json',
                            async : false,
                            success: function(response){
                              $("#process_loader").fadeOut();
                              $("#eventDetail").modal("hide");
                                $("#msg").html('<div class="col-md-12" style="margin-top:30px;width:50%;"><div class="alert alert-success">'+response.msg+'</div></div>');
                                setTimeout(function(){ $("#msg").html(""); }, 1500);
                                calendar.refetchEvents();
                            }
                        });
                      }
                  }
              });   
          }); 

          $(document).on('click', '.removeEvent', function() {
            var eventId = $("#idSchedule").val();
            var yes = confirm("Are you sure ?");
            if(yes == true){
              $("#process_loader").fadeIn();  
              $.ajax({
                url: "calendar_event_remove.php",
                type: "POST",
                data: {eventId:eventId},
                dataType: 'json',
                async : false,
                success: function(response){
                  if(response.msg == "true"){
                    $("#eventDetail").modal("hide");
                    $("#process_loader").fadeOut();
                    //location.reload(true);
                    calendar.refetchEvents();
                  }
                }
              });
            }
          });
        
      },
      // eventReceive: function (info){
      //   console.log(info);
      // },
      eventRender:function(event, element){
            element.children('.fc-timeline-event fc-h-event').css({'background-color': 'yellow'});
          },
      editable: true,
      droppable: true,
      //Job's drag drop cards function
      drop: function(info, jsEvent, ui){ 
        console.log(info);
        console.log($(info).attr("class"));
        //console.log(info.draggedEl.dataset.event.id);
        
        //console.log(info.draggedEl.dataset['event']);
        //console.log(info.draggedEl.dataset['event.id']);

        var originalEventObject = $(this).data('eventObject');
        console.log(originalEventObject);


        var eventData = JSON.parse(info.draggedEl.dataset['event']);
        //console.log(eventData);

        var id = eventData.id;
        var title = eventData.title;
        var duration = eventData.duration;

        var userId = info.resource.id;
        var eventtitle = title;
        var jobguid = id;
        

        var starttime = moment(info.dateStr).format('YYYY-MM-DD hh:mm:ss A');
        var AmPm = starttime.split(" ")[2];
        var endtime = moment(starttime).add(1, 'hours').format('YYYY-MM-DD hh:mm:ss ')+AmPm;
        
        //$('#calendar').attr("class", "").addClass( $(ui.draggable).attr("class") );
        //$('#calendar').fullCalendar('renderEvent', info, false);
        
        // $('#calendar').fullCalendar({
        //   //'renderEvent', info, false
          
        // });
        
        $("#process_loader").fadeIn();
              $.ajax({
                  url: "check_validate_datetime.php",
                  type: "POST",
                  data: {userId: userId, eventtitle: eventtitle, jobguid: jobguid,starttime: starttime, endtime: endtime},
                  dataType: 'json',
                  async : false,
                  success: function(response){
                      if(response.result == 'false'){
                        $("#process_loader").fadeOut();
                        $("#error-msg").html(response.msg);
                        return false;
                      }else{
                        $("#process_loader").fadeIn();
                        $.ajax({
                            url: "calendar_event_create.php",
                            type: "POST",
                            data: {userId: userId, eventtitle: eventtitle, jobguid: jobguid,starttime: starttime, endtime: endtime},
                            dataType: 'json',
                            async : false,
                            success: function(response){
                                if(response.result == 'success'){
                                  $("#process_loader").fadeOut();
                                  $('#addScheduleEntry').modal('hide');
                                  $("#msg").html('<div class="col-md-12" style="margin-top:30px;width:50%;"><div class="alert alert-success">'+response.msg+'</div></div>');
									                setTimeout(function(){ $("#msg").html(""); }, 1500);
                                  
                                  
                                  //calendar.render();
                                  //draggedEl.remove();
                                  //eventSource.remove();
                                  //$(this).remove();
                                  
                                  //info.draggedEl.remove();
                                  
                                  
                                  
                                   //console.log(calendar.refetchEvents());
                                  
                                  //console.log(info);
                                  //console.log(data);
                                  //console.log(calendar.refetchEvents()); 
                                  calendar.refetchEvents();
                                }
                            },
                            
                        });
                        
                      }
                  }
                  
              });   
              
              //$('#mycalendar').fullCalendar( 'renderEvent', event [, stick ] )
        }, 
      
      editable: true,
      droppable: true,
      eventDrop: function(info, delta, revertFunc, ui) {
        console.log('event drop function data:');

        console.log(info);
        //alert(JSON.stringify(info));        
        var EventId = info.oldEvent.id;

        var oldEventStartTime =  moment(info.oldEvent.start).format('YYYY/MM/DD HH:mm:ss');
        var oldEventEndTime =  moment(info.oldEvent.end).format('YYYY/MM/DD HH:mm:ss');
              
        var newEventStartTime = moment(info.event.start).format('YYYY/MM/DD HH:mm:ss');
        var newEventEndTime = moment(info.event.end).format('YYYY/MM/DD HH:mm:ss');

        //console.log(info.event.start);
        //console.log(info.event.end);
        

        if(info.oldResource=='' || info.oldResource==null){
            var oldUserId="";
        }else{
            var oldUserId = info.oldResource.id;
        }
        
        if(info.newResource=='' || info.newResource==null){
            var newUserId="";
        }else{
            var newUserId = info.newResource.id;
        }
        
        if(oldUserId!="" && newUserId != ""){
          var yes = confirm("Are you sure about this change?");
          if(yes == false){
            info.revert();
          }else{
            $("#process_loader").fadeIn();
            $.ajax({
              url: "save_drop_event_detail.php",
              type: "POST",
              data: {EventId:EventId,oldUserId:oldUserId,newUserId:newUserId,newEventStartTime:newEventStartTime,newEventEndTime:newEventEndTime},
              dataType: 'json',
              async : false,
              success: function(response){
                if(response.msg == 'success'){
                  $("#process_loader").fadeOut();
                  calendar.refetchEvents();
                }
              }
            });
          }
        }else{
          $("#process_loader").fadeIn();
            $.ajax({
              url: "save_drop_event_detail.php",
              type: "POST",
              data: {EventId:EventId,oldUserId:oldUserId,newUserId:newUserId,newEventStartTime:newEventStartTime,newEventEndTime:newEventEndTime},
              dataType: 'json',
              async : false,
              success: function(response){
                if(response.msg == 'success'){
                  $("#process_loader").fadeOut();
                  calendar.refetchEvents();
                }
              } 
            });
        } 
      },
      eventResize: function(info) {
        //alert(info.event.title + " end is now " + info.event.end.toISOString());
        //alert(JSON.stringify(info));
        if (!confirm("Are you sure about this change?")) {
          info.revert();
        }else{
          var EventId = info.oldEvent.id;
          var newEventStartTime = moment(info.event.start).format('YYYY/MM/DD HH:mm:ss');
          var newEventEndTime = moment(info.event.end).format('YYYY/MM/DD HH:mm:ss');

          //saveResizeEvent(EventId,newEventStartTime,newEventEndTime);
          $("#process_loader").fadeIn();
          $.ajax({
            url: "save_resize_event_detail.php",
            type: "POST",
            data: {EventId:EventId,newEventStartTime:newEventStartTime,newEventEndTime:newEventEndTime},
            dataType: 'json',
            async : false,
            success: function(response){
              if(response.msg == 'success'){
                $("#process_loader").fadeOut();
                calendar.refetchEvents();
                //location.reload(true);
              }
            } 
          });
        }
      },
      eventConstraint: {
        //start: moment().format('YYYY-MM-DD'),
        //end: '2100-01-01' // hard coded goodness unfortunately
        slotMinTime: '10:00' ,
        slotMaxTime: '11:00'
      },

      


    });
    calendar.render();
  });
</script>

<div class="modal fade" id="addScheduleEntry" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Add Event</h4>
          <div id="addScheduleBody">
            <form name="createEvent" method="post" id="frmEvent">
              <center>
                <input type="hidden" name="userId" id="userId" value="">
                <br><label>Event Title</label>
                <input type="text" class="form-control" name="eventtitle" id="eventtitle">
                <br><label>OR</label>
                
                <br><br><label>Select Job</label>
                <select class="form-control selectpicker" data-live-search="true" name="jobguid" id="jobguid" required>
                  <option value="">Select Job</option>
                  <?php
                      $filterparams = "AND (statusid = '100028' OR statusid = '100016' OR statusid = '100023' OR statusid = '100026' OR statusid = '1055')";
                      $jobs_qry = "SELECT * FROM $database.st_Job WHERE st_Job.tenantGuid = '$tenantGuid' AND jobstatusinternalid <> '8' AND jobinternaltypeid <> '1552' AND jobinternaltypeid <> '1553' AND accountinggroupid = '100002' AND st_Job.isdeleted = 'N' $filterparams ORDER BY jobnumber DESC";
                      echo Job::selectJobsCustom($jobs_qry, 1);
                  ?>
                </select>
                
                <small>Only Jobs In Progress or Unallocated are listed</small>
                <br><br><label>Start time</label>
                <input type="text" class="form-control" id="starttime" name="starttime" step="1" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" value="<?php echo $preFillDateStart; ?>" required readonly>
                
                <br><label>End time</label>
                <input type="text" class="form-control" id="endtime" name="endtime" step="1" min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>" value="<?php echo $preFillDateEnd; ?>" required readonly>
                <div id="error-msg" style="text-align: left;font-weight: bold;color: rad;color: red;margin: 5px;"></div>
                </center>
            </form>
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="scheduleModalSubmit" class="btn btn-success modal_default_ok">Add</button>
      </div>
    </div>
  </div>
</div>
 

  <!-- =============================================== -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content"> 
        <div class="row">
          <form method="get" name="search">
            <div class="col-md-2">
              <label style="width:100%;">&nbsp;</label>
              <input type="text" class="form-control" id="fromDate" name="fromDate" value="<?php if($fromDate){ echo $fromDate; } ?>" readonly placeholder="From Date">
            </div>
            <div class="col-md-2">
              <label style="width:100%;">&nbsp;</label>
              <input type="text" class="form-control" id="toDate" name="toDate" value="<?php if($toDate){ echo $toDate; } ?>" readonly placeholder="To Date">
            </div>
            <div class="col-md-2">
              <label style="width:100%;">&nbsp;</label>
              <select class="form-control selectpicker" data-live-search="true" name="startTime" id="startTime">
                <option value="">Select Start Time</option>
                <?php 
                  for($i = 0; $i < 24; $i++):
                  $is = sprintf("%02d", $i).":00:00";
                  $startTime = $_GET["startTime"];
                  if($startTime==""){
                    $startTime = "05:00:00";
                  }
                  if($is==$startTime){
                    $selected = 'selected="selected"';
                  }else{
                    $selected = '';
                  }
                ?>
                  <option value="<?= sprintf("%02d", $i).':00:00'; ?>" <?php echo $selected; ?>><?= $i % 12 ? $i % 12 : 12 ?>:00 <?= $i >= 12 ? 'pm' : 'am' ?></option>
                <?php endfor ?>
              </select>
            </div>
            <div class="col-md-2">
              <label style="width:100%;">&nbsp;</label>
              <select class="form-control selectpicker" data-live-search="true" name="endTime" id="endTime">
                <option value="">Select End Time</option>
                <?php 
                  for($i = 0; $i < 24; $i++):
                  $is = sprintf("%02d", $i).":00:00";
                  $endTime = $_GET["endTime"];
                  if($endTime==""){
                    $endTime = "18:00:00";
                  }
                  if($is==$endTime){
                    $selected = 'selected="selected"';
                  }else{
                    $selected = '';
                  } 
                ?>
                  <option value="<?= sprintf("%02d", $i).':00:00'; ?>" <?php echo $selected; ?>><?= $i % 12 ? $i % 12 : 12 ?>:00 <?= $i >= 12 ? 'pm' : 'am' ?></option>
                <?php endfor ?>
              </select>
            </div>

            <div class="col-md-2">
              <label style="width:100%;">&nbsp;</label>
              <select class="form-control" data-live-search="true" name="searchWeek" id="searchWeek">
                <option value="">Select Week Filter</option>
                <option value="1" <?php if($searchWeek==1) echo "selected"; ?>>Week 1</option>
                <option value="2" <?php if($searchWeek==2) echo "selected"; ?>>Week 2</option>
                <option value="3" <?php if($searchWeek==3) echo "selected"; ?>>Week 3</option>
                <option value="4" <?php if($searchWeek==4) echo "selected"; ?>>Week 4</option>
              </select>
            </div>
            <div class="col-md-2">
              <label style="width:100%;">&nbsp;</label>
              <button type="submit" id="FilterSubmit" class="btn btn-success FilterTime">Filter</button>
              <a href="calendar.php" class="btn btn-primary ClearFilterTime">Clear</a>
            </div>
          </form>
        </div>


        <div class="row"> 
            <div id="msg"></div>
            <div class="col-md-12" id = "calJob"> 
                <div id='calendar-container'>
                  <div id='calendar'></div>
                </div>

                <!-- Job Sidebar code here-->
                <aside id='jobSidebar' class='col-md-12'>
                    <h1 style="color:white; text-align:center;">JOBS</h1>
                    
                    <button type="button" class="btn btn-info" onclick="showFilter()">Filter</button>
                    <a href="calendar.php" class="btn btn-default pull-right">Reset</a>

                    
                    <!-- Create a filter box-->
                    <div class="box" id = "filterbox" style = "display:none;">
                      <div class="box-body">
                        <form action="#" id="searchform" method="POST" role="form">
                        <input type="hidden" class="form-control" name="submit_results" id="submit_results" value="1"></input>
                        <div class="form-group">
                          <div class="col-md-12">
                            <label>Job #</label>
                            <input type="text" class="form-control" name="jobno" id="jobno" value="<?php echo $jobno; ?>"></input>
                          </div>
                          <div class="col-md-12">
                            <label>Workflow Status</label>
                            <select class="form-control" name="status" id="status">
                              <?php
                              if ($status == ''){
                                echo '<option value="">All Statuses</option>';
                                echo Job::selectJobStatus($status);
                              } else {
                                echo Job::selectJobStatus($status);
                                echo '<option value="">All Statuses</option>';
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                        <br><br><br>
                        <div class="form-group">

                          <div class="col-md-12">
                            <label>Client</label>
                            <select class="form-control selectpicker" data-live-search="true" name="client" id="client" required>
                              <?php
                              if ($client == ''){
                                echo '<option data-tokens="">All Client</option>';
                                echo Client::selectClient($client, 1, 1);
                              } else {
                                echo Client::selectClient($client, 1, 1);
                                echo '<option value="">All Clients</option>';
                              }
                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <div class="col-md-12">
                              <label>Site</label>
                              <select class="form-control selectpicker" data-live-search="true" name="site" id="site">
                                <?php
                                if ($site == ''){
                                  echo '<option value="">All Sites</option>';
                                } else {
                                  echo Sites::selectClientSiteswithAddress($client, $site, 1, 1);
                                  echo '<option value="">All Sites</option>';
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>

                          <div class="col-md-12">
                            <label>Job Category</label>
                            <select class="form-control" name="category" id="category">
                              <?php
                              if ($category == ''){
                                echo '<option value="">All Categories</option>';
                                echo Job::selectJobCategories($category, 0);
                              } else {
                                echo Job::selectJobCategories($category, 0);
                                echo '<option value="">All Categories</option>';
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-12">
                            <label>Responsible Person</label>
                            <select class="form-control" name="responsible" id="responsible">
                              <?php
                              if ($responsible == ''){
                                echo User::selectEmployedUsers($responsible);
                              } else {
                                echo User::selectEmployedUsers($responsible);
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-12">
                            <label>Sort By: </label><br>

                            <!-- <input type="radio" id="asc" name="ascending" value="ascending">
                            <label for="asc">Ascending</label>
                            <input type="radio" id="desc" name="descending" value="descending">
                            <label for="desc">Descending</label> -->

                            

                            <select class="form-control" name="sorting" id="sorting">
                              <option value = "status">Status</option>
                              <option value = "jobNo">Job Number</option>
                              <option value = "priority">Priority</option>
                              <option value = "category">Category</option>
                              <option value = "jobdescription">Job Description</option>
                              <option value = "client">Client Name</option>
                              <option value = "site">Site</option>
                              <option value = "created">Created Date</option>
                              <option value = "target">Target Date</option>
                            </select>
                            <input type="radio" id="ascending" name="asc/desc" value="asc" checked>
                            <span style = "position:relative;margin-right:15px;">Ascending<i style="font-size:20px;position:absolute; top:10%;margin:0 3px;" class="fa fa-sort-up" aria-hidden="true"></i></span>
                            <input type="radio" id="descending" name="asc/desc" value="desc">
                            <span style = "position:relative">Descending<i style="font-size:20px;position:absolute;bottom:10%; margin:0 3px;" class="fa fa-sort-down" aria-hidden="true"></i></span>
                          </div>
                          
                          <div class="col-md-12" style = "margin:5px 0;">
                            Show only <input type="text" style = "width:30%;display:inline;" class="form-control" name="limit" id="limit" value="10"></input> results!
                          </div>
                          
                            
                            
                           
                          
                            

                      </div>                      
                      </form>

                        <!-- /.box-body -->
                      <div class="box-footer clearfix">
                          <div class="pull-right">
                            <a onclick="resetForm()" class="btn btn-light">Clear</a>
                            <!-- <button class = "btn btn-secondary active">Clear</button> -->
                            <button onclick = "searchcard()" class="btn btn-primary">Search</button>
                          </div>
                        </div>
                      </div>

                    <!-- Display cards   -->
                    <div id="external-events">

                          <?php
                            function currentUrl( $trim_query_string = false ) {
                              $pageURL = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
                              $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                              if( ! $trim_query_string ) {
                                  return $pageURL;
                              } else {
                                  $url = explode( '?', $pageURL );
                                  return $url[1];
                              }
                          }

                            function countJobRecords($job_qry) {
                                require('include/config.php');
                                $job_query = mysqli_query($mysqli, $job_qry);
                                $count = mysqli_num_rows($job_query);
                                $time_post = microtime(true);
                                return $count;
                            }
                            
                            function printServiceRow($service_row_qry) {
                              require('config.php');
                              
                              //echo $service_row_qry;
                              $service_row_query = mysqli_query($mysqli, $service_row_qry);
                              while ($row = mysqli_fetch_array($service_row_query)) {
                                $idCheckSchedule = $row['idCheckSchedule'];
                                $idSiteService = $row['idSiteServices'];
                                $idSite = $row['siteId'];
                                $checkId = $row['checkId'];
                                $checkGroupId = $row['checkGroupId'];
                                $checkInterval = $row['checkInterval'];
                                $subcategoryId = $row['subcategoryId'];
                                $serviceMethodId = $row['serviceMethodId'];
                                $type = $row['type'];
                      
                                $started = $row['started'];
                      
                                //$serviceAlreadyActive = Service::serviceStillActive($idSite, $type);
                                $serviceAlreadyActive = 'false';
                      
                                $serviceType = Contract::getContractType($type);
                                $serviceIcon = Contract::getContractType($type, 'icon');
                                return $serviceTypeBadge = '<img src="include/get_aws_object.php?id='.$type.'&uniqueType=8" width="20px" height="20px" title="'.$serviceType.'"></img>';
                                
                                

                              }
                            }




                            function printJobRows($job_qry) {
                              require('include/config.php');
                              $job_query = mysqli_query($mysqli, $job_qry);
                              
                              //echo $job_query;
                              while ($row = mysqli_fetch_array($job_query)) {
                                $badges = '';
                                $jobId = $row['jobid'];
                                $jobguid = $row['jobguid'];

                                //get job properties
                                $query = SmartTrade::getPropsFromGuid($jobguid);
                                foreach ($query as $key => $value) {
                                    $$key = $value;
                                }

                                $convertedCreatedDateDisp = $convertedCreatedDate;

                                if ($overrideCreatedDate <> '') {
                                  $convertedCreatedDate = $overrideCreatedDate;
                                }


                                $jobnumber = $prefix.$jobnumber.$suffix;

                                

                                $state_qry = "SELECT * FROM job_status WHERE statusId = $statusid LIMIT 1";
                                $state_query = mysqli_query($mysqli, $state_qry);
                                while ($row2 = mysqli_fetch_array($state_query)) {
                                  $idJobStatus = $row2['idJobStatus'];
                                  $statusId = $row2['statusId'];
                                  $statedescription = $row2['description'];
                                  $labelType = $row2['labelType'];
                                  $status = '<span class="label label-'.$labelType.'">'.$statedescription.'</span>';
                                }

                                
                          
                                $now = strtotime('now');

                                $begin = strtotime($createddate);
                                $now = date('Y-m-d H:i:s');

                                //$response = SmartTrade::jobResponseTime($jobpriorityid);

                                $pri_qry = "SELECT * FROM priority_array WHERE STpriorityId = '$jobpriorityid' LIMIT 1";
                                //echo $pri_qry;
                                $pri_query = mysqli_query($mysqli, $pri_qry);
                                while ($row2 = mysqli_fetch_array($pri_query)) {
                                  $shortpriority = $row2['shortDescription'];
                                  $pridescription = $row2['description'];
                                  $responseTime = $row2['responseTime'];
                                  $resolutionTime = $row2['resolutionTime'];
                                  //$priority = $shortpriority.' - '.$pridescription;
                                  $priority = '<span title="'.$pridescription.'">'.$shortpriority.'</span>';
                                }
                                $resolutionTime = '+'.$resolutionTime;
                                //$resolutionTime = '+48 hours';


                                
                                //status colors
                                $green = "success";
                                $red = "danger";
                                $orange = "warning";
                                $grey = "default";
                                $blue = 'primary';


                                
                                switch ($shortpriority) {
                                  case 'P1':
                                    $priority = '<span class="label label-danger">'.$priority.'</span>';
                                    break;
                                  case 'P2':
                                    $priority = '<span class="label label-warning">'.$priority.'</span>';
                                    break;
                                  case 'P3':
                                    $priority = '<span class="label label-primary">'.$priority.'</span>';
                                    //  $priority = '<font color="red">'.$priority.'</font>';
                                    break;
                                  case 'P4':
                                    $priority = '<span class="label label-info">'.$priority.'</span>';
                                    // $priority = '<font color="orange">'.$priority.'</font>';
                                    break;
                                  case 'P5':
                                     $priority = '<span class="label label-success">'.$priority.'</span>';
                                      // if($labelType == $green){
                                      //   $priority = '<font color="darkgreen">'.$priority.'</font>';
                                      // }
                                      // else{
                                      //   $priority = '<font color="green">'.$priority.'</font>';
                                      // }
                                      break;
                                    case 'Normal':
                                      $priority = '<span class="label label-default">'.$priority.'</span>';
                                    break;
                                  default:
                                    $priority = $shortpriority;
                                    break;
                                }

                              
                                // //no serial. Add to badges
                                if ($secondsTillTarget == 0) {
                                  //$kpiTarget = '<font color="red">'.$kpiTarget.'</font>';
                                  $badges= '<span class="label label-danger" title="KPI has been missed">
                                  <span class="glyphicon glyphicon-hourglass"></span>
                                  </span>';
                                } else {
                                  $badges = '';
                                }

                                if ($extendedAt <> '') {
                                  $kpiTarget = '<font color="green">'.$kpiTarget.'</font>';
                                  $badges = '<span class="label label-success" title="Job was extended at '.$extendedAt.'">
                                  <span class="fa fa-pause"></span>
                                  </span>';
                                }

                                if ($kpiMet == '1') {
                                  $kpiTarget = '<font color="green">'.$kpiTarget.'</font>';
                                  $badges = '<span class="label label-success" title="KPI was met">
                                  <span class="fa fa-check"></span>
                                  </span>';
                                }

                                
                                


                                // //no serial. Add to badges
                                if ($secondsTillTarget == 0) {
                                  //$kpiTarget = '<font color="red">'.$kpiTarget.'</font>';
                                  $badges= '<span class="label label-danger" title="KPI has been missed">
                                  <span class="glyphicon glyphicon-hourglass"></span>
                                  </span>';
                                } else {
                                  $badges = '';
                                }

                                if ($extendedAt <> '') {
                                  $kpiTarget = '<font color="green">'.$kpiTarget.'</font>';
                                  $badges = '<span class="label label-success" title="Job was extended at '.$extendedAt.'">
                                  <span class="fa fa-pause"></span>
                                  </span>';
                                }

                                if ($kpiMet == '1') {
                                  $kpiTarget = '<font color="green">'.$kpiTarget.'</font>';
                                  $badges = '<span class="label label-success" title="KPI was met">
                                  <span class="fa fa-check"></span>
                                  </span>';
                                }
                                // //no cost. Add to badges
                                // if ($cost == '0.00') {
                                //   $badges = $badges.' <span class="label label-success" title="Asset is missing a Cost">$</span>';
                                // }
                                // //no image. Add to badges
                                // if ($imageId == '0') {
                                //   $badges = $badges.' <span class="label label-warning" title="Asset is missing an image">
                                //   <span class="glyphicon glyphicon-picture"></span>
                                //   </span>';
                                // }

                                //$site = Sites::getSiteName($siteId);
                                //$location = Sites::getLocationName($locationId);

                                $clientName = Client::getClientFromGUI($entityguid);
                                $siteName = Client::getClientFromGUI($entitysiteguid);
                                $jobcategory = Job::getJobCategory($jobcategoryid);
                                
                                
                                                      
                                $serviceType = Contract::getContractTypeFromSTid($jobcategoryid);
                                $serviceTypeId = Contract::getContractTypeFromSTid($jobcategoryid, 'idContractTypes');
                                $serviceTypeBadge = '<img style = "margin-right:5px;" src="include/get_aws_object.php?id='.$serviceTypeId.'&uniqueType=8" width="20px" height="20px" title="'.$serviceType.'"></img>';
                                
                                // $jobs_qry_test = "SELECT * FROM $database.st_Job $joinparams WHERE st_Job.tenantGuid = '$tenantGuid' AND jobstatusinternalid <> '8' AND jobinternaltypeid <> '1552' AND jobinternaltypeid <> '1553' AND accountinggroupid = '100002' AND st_Job.isdeleted = 'N' $filterparams ORDER BY jobnumber DESC"; 
                                
                                // $sort = $_POST['sorting'];
                                //switch for status color, change color of the card accordingly
                                //Display jobs information in the card
                                switch($labelType){
                                  case $green: //status green
                                    //echo $jobs_qry_test;
                                      // printServiceRow();
                                          echo '
                                          <article class="fc-event fc-h-event" style="background-color:#1A821A;"
                                          data-event='."'"; ?>{ "id": "<?php echo $jobguid; ?>", "title": "<?php echo $jobnumber.' - '.$description; ?>", "duration": "01:00" }<?php echo "'".'>
                                            <div class = "fc-event-main" style="display:block;"> '; 
                                            echo '
                                              <div id="jobCard" >

                                                <h3 class="card-title">'.$serviceTypeBadge.''.$jobnumber.'</h3>
                                                <p style="color:#4D4D4D; font-weight:bold;"> Priority: '.$priority.'</p>
      
                                                
                                                
                                                <button style = "margin:5px;" class="btn btn-primary btn-lg btn-block" value="'.$jobguid.'" onclick="showMore(this)">Read More</button>

                                                <div id="collapseElement'.$jobguid.'" style = "display:none;">
                                                  
                                                    <p class="jobCate">'.$jobcategory.'</p>
                                                    <p class="jobDes">'.$description.'</p>
                                                    <p class="clientName">Client: '.$clientName.'</p>
                                                    <h4 class="siteName">Site: '.$siteName.'</h4>
                                                    <p class="createdDate">Created: '.$convertedCreatedDateDisp.'</p>
                                                    <p class="kpiTarget">Target: '.$kpiTarget.'</p>
                                                    
                                                </div>
                                                
                                              </div>
                                            </div> 
                                          </article>';
                                  break;
                                  
                                  case $red: //C40000
                                    
                                    echo '
                                    <article class="fc-event fc-h-event" style="background-color:#C40000;"
                                    data-event='."'"; ?>{ "id": "<?php echo $jobguid; ?>", "title": "<?php echo $jobnumber.' - '.$description; ?>", "duration": "01:00" }<?php echo "'".'>
                                      <div class = "fc-event-main" style="display:block;"> '; 
                                      echo '
                                        <div id="jobCard" >
                                        
                                        <h3 class="card-title">'.$serviceTypeBadge.''.$jobnumber.'</h3>
                                          <p style="color:#4D4D4D; font-weight:bold;"> Priority: '.$priority.'</p>

                                          
                                          
                                          <button style = "margin:5px;" class="btn btn-primary btn-lg btn-block" value="'.$jobguid.'" onclick="showMore(this)">Read More</button>

                                                <div id="collapseElement'.$jobguid.'" style = "display:none;">
                                                  
                                                    <p class="jobCate">'.$jobcategory.'</p>
                                                    <p class="jobDes">'.$description.'</p>
                                                    <p class="clientName">Client: '.$clientName.'</p>
                                                    <h4 class="siteName">Site: '.$siteName.'</h4>
                                                    <p class="createdDate">Created: '.$convertedCreatedDateDisp.'</p>
                                                    <p class="kpiTarget">Target: '.$kpiTarget.'</p>
                                                  
                                                </div>
                                          
                                        </div>
                                      </div> 
                                    </article>';
                                  break;
                                  
                                  case $orange: //FF8000
                                    echo '
                                    <article class="fc-event fc-h-event" style="background-color:#FF8000;"
                                    data-event='."'"; ?>{ "id": "<?php echo $jobguid; ?>", "title": "<?php echo $jobnumber.' - '.$description; ?>", "duration": "01:00" }<?php echo "'".'>
                                      <div class = "fc-event-main" style="display:block;"> '; 
                                      echo '
                                        <div id="jobCard" >
                                        <h3 class="card-title">'.$serviceTypeBadge.''.$jobnumber.'</h3>
                                          <p style="color:#4D4D4D; font-weight:bold;"> Priority: '.$priority.'</p>

                                          
                                          
                                          <button style = "margin:5px;" class="btn btn-primary btn-lg btn-block" value="'.$jobguid.'" onclick="showMore(this)">Read More</button>

                                                <div id="collapseElement'.$jobguid.'" style = "display:none;">
                                                  
                                                    <p class="jobCate">'.$jobcategory.'</p>
                                                    <p class="jobDes">'.$description.'</p>
                                                    <p class="clientName">Client: '.$clientName.'</p>
                                                    <h4 class="siteName">Site: '.$siteName.'</h4>
                                                    <p class="createdDate">Created: '.$convertedCreatedDateDisp.'</p>
                                                    <p class="kpiTarget">Target: '.$kpiTarget.'</p>
                                                  
                                                </div>
                                          
                                        </div>
                                      </div> 
                                    </article>';
                                  break;

                                  case $grey: //CCCCCC
                                    echo '
                                          <article class="fc-event fc-h-event" style="background-color:#CCCCCC;"
                                          data-event='."'"; ?>{ "id": "<?php echo $jobguid; ?>", "title": "<?php echo $jobnumber.' - '.$description; ?>", "duration": "01:00" }<?php echo "'".'>
                                            <div class = "fc-event-main" style="display:block;"> '; 
                                            echo '
                                              <div id="jobCard" >
                                                <h3 class="card-title">'.$serviceTypeBadge.''.$jobnumber.'</h3>
                                                <p style="color:#4D4D4D; font-weight:bold;"> Priority: '.$priority.'</p>
      
                                                
                                                
                                                <button style = "margin:5px;" class="btn btn-primary btn-lg btn-block" value="'.$jobguid.'" onclick="showMore(this)">Read More</button>

                                                <div id="collapseElement'.$jobguid.'" style = "display:none;">
                                                  
                                                    <p class="jobCate">'.$jobcategory.'</p>
                                                    <p class="jobDes">'.$description.'</p>
                                                    <p class="clientName">Client: '.$clientName.'</p>
                                                    <h4 class="siteName">Site: '.$siteName.'</h4>
                                                    <p class="createdDate">Created: '.$convertedCreatedDateDisp.'</p>
                                                    <p class="kpiTarget">Target: '.$kpiTarget.'</p>
                                                  
                                                </div>
                                                
                                              </div>
                                            </div> 
                                          </article>';
                                  break;

                                  case $blue: //007FFF
                                    echo '
                                          <article class="fc-event fc-h-event" style="background-color:#007FFF;"
                                          data-event='."'"; ?>{ "id": "<?php echo $jobguid; ?>", "title": "<?php echo $jobnumber.' - '.$description; ?>", "duration": "01:00" }<?php echo "'".'>
                                            <div class = "fc-event-main" style="display:block;"> '; 
                                            echo '
                                              <div id="jobCard" >
                                                <h3 class="card-title">'.$serviceTypeBadge.''.$jobnumber.'</h3>
                                                <p style="color:#4D4D4D; font-weight:bold;"> Priority: '.$priority.'</p>
      
                                                
                                                
                                                <button style = "margin:5px;" class="btn btn-primary btn-lg btn-block" value="'.$jobguid.'" onclick="showMore(this)">Read More</button>

                                                <div id="collapseElement'.$jobguid.'" style = "display:none;">
                                                  
                                                    <p class="jobCate">'.$jobcategory.'</p>
                                                    <p class="jobDes">'.$description.'</p>
                                                    <p class="clientName">Client: '.$clientName.'</p>
                                                    <h4 class="siteName">Site: '.$siteName.'</h4>
                                                    <p class="createdDate">Created: '.$convertedCreatedDateDisp.'</p>
                                                    <p class="kpiTarget">Target: '.$kpiTarget.'</p>
                                                  
                                                </div>
                                                
                                              </div>
                                            </div> 
                                          </article>';
                                  break;
                                }
                              }

                            }

                            
                            $limit = '';
                            
                            //start filtering
                            $joinparams = '';
                            $filterparams = '';
        
        
        
                            if (isset($_GET['responsible'])) {
                              $submit_results = 1;
                              $responsible = mysqli_real_escape_string($mysqli, $_GET['responsible']);
                              if ($responsible <> '0') {
                                $usersEmail = User::getProp($responsible, 'email');
                                $usersId = SmartTrade::getUserIdfromEmail($usersEmail);
                                if ($usersId == 0) {
                                  $responsible = 0;
                                } else {
                                  $responsible = SmartTrade::getUserProp($usersId, 'personguid');
                                }
                              }
                            } else {
                              $responsible = 0;
                            }
        
                            if (isset($_POST['responsible'])) {
                              $submit_results = 1;
                              $responsible = mysqli_real_escape_string($mysqli, $_POST['responsible']);
                              if ($responsible <> '0') {
                                $usersEmail = User::getProp($responsible, 'email');
                                $usersId = SmartTrade::getUserIdfromEmail($usersEmail);
                                if ($usersId == 0) {
                                  $responsible = 0;
                                } else {
                                  $responsible = SmartTrade::getUserProp($usersId, 'personguid');
                                }
                              }
                            } else {
                              $responsible = 0;
                            }
        
                            if ($responsible <> '0') {
                              $filterparams .= " AND responsiblepersongui = '$responsible'";
                            } else {
                              $filterparams .= '';
                            }
        
        
                            if (isset($_POST['client'])) {
                              $client = mysqli_real_escape_string($mysqli, $_POST['client']);
                              if ($client <> '' AND $client <> 'All Client') {
                                $clientguid = Client::getClientGUI($client);
                                $filterparams .= " AND st_Job.entityguid = '$clientguid'";
                              }
                            }
        
                            if (isset($_GET['client'])) {
                              $client = mysqli_real_escape_string($mysqli, $_GET['client']);
                              if ($client <> '' AND $client <> 'All Client') {
                                $clientguid = Client::getClientGUI($client);
                                $filterparams .= " AND st_Job.entityguid = '$clientguid'";
                              }
                            }
        
                            if (isset($_POST['site'])) {
                               $site = mysqli_real_escape_string($mysqli, $_POST['site']);
                               if ($site <> '') {
                                 $siteguid = Client::getClientGUI($site);
                                 $filterparams .= " AND st_Job.entitysiteguid = '$siteguid'";
                               }
                            }
        
                            if (isset($_POST['category'])) {
                               $category = mysqli_real_escape_string($mysqli, $_POST['category']);
                               if ($category <> '') {
                                 $filterparams .= " AND st_Job.jobcategoryid = $category";
                               }
                            } else {
                             if (isset($_GET['category'])) {
                                $submit_results = 1;
                                $category = mysqli_real_escape_string($mysqli, $_GET['category']);
                                if ($category <> '') {
                                  $filterparams .= " AND st_Job.jobcategoryid = $category";
                                }
                             }
                            }
        
        
                            if (isset($_GET['extended'])) {
                              $submit_results = 1;
                              $filterparams .= " AND extendedActive = 1";
                            }
        
        
                            if (isset($_GET['status'])) {
                              $status = mysqli_real_escape_string($mysqli, $_GET['status']);
                              //$statusId = SmartTrade::jobStatus($status);
                              if ($status <> '') {
                                $filterparams .= " AND statusid = '".$status."' ";
                              }
        
        
                              $submit_results = 1;
        
                              if ($statusId <> '') {
                                $filterparams .= " AND $statusId";
                              }
                            }
        
                            if (isset($_POST['status'])) {
                              $status = mysqli_real_escape_string($mysqli, $_POST['status']);
                              //$statusId = SmartTrade::jobStatus($status);
                              if ($status <> '') {
                                $filterparams .= " AND statusid = '".$status."' ";
                              }
                              //echo '<hr><h1>'.$statusId.'</h1>';
                              $submit_results = 1;
        
                              if ($statusId <> '') {
                                $filterparams .= " AND $statusId";
                              }
                            }
        
                            if (isset($_POST['jobno'])) {
                              $jobno = mysqli_real_escape_string($mysqli, $_POST['jobno']);
                              
                              if ($jobno <> '') {
                                $filterparams .= " AND st_Job.jobnumber LIKE '%$jobno%'";
                              }
                            }
        
                            

                            $jobs_qry = "SELECT * FROM $database.st_Job $joinparams WHERE st_Job.tenantGuid = '$tenantGuid' AND jobstatusinternalid <> '8' AND jobinternaltypeid <> '1552' AND jobinternaltypeid <> '1553' AND accountinggroupid = '100002' AND st_Job.isdeleted = 'N' $filterparams ORDER BY jobnumber DESC";
                            $service_qry = "SELECT * FROM $database";
                            
        
                               if (isset($_GET['kpis']))  {
                                 $submit_results = 1;
        
                                 $jobs_qry = "SELECT * FROM $database.st_Job $joinparams WHERE tenantGuid = '$tenantGuid' AND completedAt is null
                                 AND secondsTillTarget < 14400
                                 AND jobstatusinternalid <> '8'
                                 AND jobinternaltypeid <> '1552'
                                 AND jobtypeid <> '1503'
                                 AND isdeleted = 'N'
                                 AND statusid <> '100011'
                                 AND convertedCreatedDate > '2017-07-01 00:00:00'
                                 AND extendedActive <> '1'
                                 AND unknownCompletion = '1'
                                 AND accountinggroupid <> '100001'
                                 $filterparams
                                 ORDER BY kpiTarget ASC";
                               }
        
        
        
                              if (isset($_POST['submit_results'])) {
                                $submit_results = 1;
                              }
        
                              if ($submit_results == 1) {
                                //echo $jobs_qry;

                                $data = printJobRows($jobs_qry.' '.$limit);
                                $records = countJobRecords($jobs_qry);
                              } else {
        
                                //show 10 most recent jobs when no search parameters
                                $jobs_qry = "SELECT * FROM $database.st_Job WHERE st_Job.tenantGuid = '$tenantGuid' AND jobstatusinternalid <> '8' AND jobinternaltypeid <> '1552' AND jobinternaltypeid <> '1553' AND accountinggroupid = '100002' AND st_Job.isdeleted = 'N' ORDER BY convertedCreatedDate DESC";
                                $limit = 'LIMIT 10';
                                $data = printJobRows($jobs_qry . ' ' . $limit);
        
                              }
        
        
        
        
                        //get page with end variables
                        $pagevars = currentUrl(true);

      
                              
      
                            
                            
        
                          ?>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    
  </div>
  
  <!-- /.content-wrapper -->

<?php
  include('include/footer.php');
?>

<div class="modal fade" id="eventDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Event History</h4>

          <div id="addScheduleBody">
            <form name="updateEvent" method="post" id="frmEventUpdate">
              <center>
                <div id="eventHistory">
                </div>  
                <div id="error-msg-update" style="text-align: left;font-weight: bold;color: rad;color: red;margin: 5px;"></div>
              </center>
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="scheduleModalSubmit" class="btn btn-success updateEvent">Update</button>
        <button type="button" id="scheduleModalSubmit" class="btn btn-danger removeEvent">Delete</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  
  $('#fromDate').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate: new Date()
  });
  $('#toDate').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate: new Date()
  });
  
  $('#starttime').datetimepicker({
    format: 'YYYY-MM-DD hh:mm:00 A',
    minDate: new Date()
  });
  
  $('#endtime').datetimepicker({
    format: 'YYYY-MM-DD hh:mm:00 A',
    minDate: new Date()
  });
   
  function loadProfilephoto(){
    <?php
      $users_qry1 = "SELECT idUsers,profilePicture FROM users WHERE tenantGuid = '$tenantGuid' AND employed = 1 AND active = 1 AND ( mechanicalStaff = 1 OR electricalStaff = 1 OR admin = 1)  ORDER BY firstname ASC";
      $users_query1 = mysqli_query($mysqli, $users_qry1);
      while ($row1 = mysqli_fetch_array($users_query1)) {
        $idUsers = $row1['idUsers'];
        $profilePicture = $row1['profilePicture'];
        $profilePicture = $userdomain.'/img/profiles/'.$profilePicture;
    ?>
      setTimeout(function(){ 
        $('.fc-datagrid-cell-main-<?php echo $idUsers; ?>').append("<img class='images' src='<?php echo $siteroot.$profilePicture; ?>' height='20px' width='20px' style='float:left;margin-left:10px;'>");
      }, 100);
    <?php
      }
    ?>
  }
  
  loadProfilephoto();
  $(document).on("click",".fc-icon-minus-square",function() {
    $(".images").remove();
    loadProfilephoto();
  });

  
</script>
<style type="text/css">
  .fc-datagrid-cell-cushion .fc-icon {
display: none;
}
</style>
</html>
