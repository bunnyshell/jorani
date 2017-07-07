<?php
/**
 * This view displays the list of leave requests submitted to a manager.
 * @copyright  Copyright (c) 2014-2017 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.1.0
 */
?>

<h2><?php echo lang('requests_index_title');?><?php echo $help;?></h2>

<?php echo $flash_partial_view;?>

<p><?php echo lang('requests_index_description');?></p>

<div class="row">
    <div class="span3">
        <?php echo lang('requests_index_thead_type');?>
        <select name="cboLeaveType" id="cboLeaveType">
            <option value="" selected></option>
        <?php foreach ($types as $type): ?>
            <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
        <?php endforeach ?>
        </select>&nbsp;&nbsp;
    </div>
<?php
$disable = "";
$checked = "checked";
if ($showAll == FALSE) {
    $disable = "disabled";
    $checked = "";
}
?>
    <div class="span1">&nbsp;</div>
    <div class="span8">
    <span class="label"><input type="checkbox" <?php echo $checked;?> id="chkPlanned" class="filterStatus" <?php echo $disable;?>> &nbsp;<?php echo lang('Planned');?></span> &nbsp;
    <span class="label label-success"><input type="checkbox" <?php echo $checked;?> id="chkAccepted" class="filterStatus" <?php echo $disable;?>> &nbsp;<?php echo lang('Accepted');?></span> &nbsp;
    <span class="label label-warning"><input type="checkbox" checked id="chkRequested" class="filterStatus"> &nbsp;<?php echo lang('Requested');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" <?php echo $checked;?> id="chkRejected" class="filterStatus" <?php echo $disable;?>> &nbsp;<?php echo lang('Rejected');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" <?php echo $checked;?> id="chkCancellation" class="filterStatus" <?php echo $disable;?>> &nbsp;<?php echo lang('Cancellation');?></span> &nbsp;
    <span class="label label-important" style="background-color: #ff0000;"><input type="checkbox" <?php echo $checked;?> id="chkCanceled" class="filterStatus" <?php echo $disable;?>> &nbsp;<?php echo lang('Canceled');?></span>
    </div>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="leaves" width="100%">
    <thead>
        <tr>
            <th><?php echo lang('requests_index_thead_id');?></th>
            <th><?php echo lang('requests_index_thead_fullname');?></th>
            <th><?php echo lang('requests_index_thead_startdate');?></th>
            <th><?php echo lang('requests_index_thead_enddate');?></th>
            <th><?php echo lang('requests_index_thead_duration');?></th>
            <th><?php echo lang('requests_index_thead_type');?></th>
            <th><?php echo lang('requests_index_thead_status');?></th>
            <th><?php echo lang('requests_index_thead_requested_date');?></th>
            <th><?php echo lang('requests_index_thead_last_change');?></th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($requests as $requests_item):
    $date = new DateTime($requests_item['startdate']);
    $tmpStartDate = $date->getTimestamp();
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($requests_item['enddate']);
    $tmpEndDate = $date->getTimestamp();
    $enddate = $date->format(lang('global_date_format'));
    if ($this->config->item('enable_history') == TRUE){
      if($requests_item['request_date'] == NULL){
        $tmpRequestDate = "";
        $requestdate = "";
      }else{
        $datetimeRequested = new DateTime($requests_item['request_date']);
        $tmpRequestDate = $datetimeRequested->getTimestamp();
        $requestdate = $datetimeRequested->format(lang('global_date_format'));
      }
      if($requests_item['change_date'] == NULL){
        $tmpLastChangeDate = "";
        $lastchangedate = "";
      }else{
        $datetimelastChanged = new DateTime($requests_item['change_date']);
        $tmpLastChangeDate = $datetimelastChanged->getTimestamp();
        $lastchangedate = $datetimelastChanged->format(lang('global_date_format'));
      }
    }
    ?>
    <tr>
        <td data-order="<?php echo $requests_item['leave_id']; ?>">
            <a href="<?php echo base_url();?>leaves/requests/<?php echo $requests_item['leave_id']; ?>" title="<?php echo lang('requests_index_thead_tip_view');?>"><?php echo $requests_item['leave_id']; ?></a>
            &nbsp;
            <div class="pull-right">
                <a href="<?php echo base_url();?>leaves/requests/<?php echo $requests_item['leave_id']; ?>" title="<?php echo lang('requests_index_thead_tip_view');?>"><i class="icon-eye-open"></i></a>
                &nbsp;
                <a href="#" class="lnkAccept" data-id="<?php echo $requests_item['leave_id']; ?>" title="<?php echo lang('requests_index_thead_tip_accept');?>"><i class="icon-ok"></i></a>
                &nbsp;
                <a href="#" class="lnkReject" data-id="<?php echo $requests_item['leave_id']; ?>" title="<?php echo lang('requests_index_thead_tip_reject');?>"><i class="icon-remove"></i></a>
                <?php if ($this->config->item('enable_history') === TRUE) { ?>
                &nbsp;
                <a href="#" class="show-history" data-id="<?php echo $requests_item['leave_id'];?>" title="<?php echo lang('requests_index_thead_tip_history');?>"><i class="icon-time"></i></a>
                <?php } ?>
            </div>
        </td>
        <td><?php echo $requests_item['firstname'] . ' ' . $requests_item['lastname']; ?></td>
        <td data-order="<?php echo $tmpStartDate; ?>"><?php echo $startdate . ' (' . lang($requests_item['startdatetype']). ')'; ?></td>
        <td data-order="<?php echo$tmpEndDate; ?>"><?php echo $enddate . ' (' . lang($requests_item['enddatetype']) . ')'; ?></td>
        <td><?php echo $requests_item['duration']; ?></td>
        <td><?php echo $requests_item['type_name']; ?></td>
        <?php
        switch ($requests_item['status']) {
            case 1: echo "<td><span class='label'>" . lang($requests_item['status_name']) . "</span></td>"; break;
            case 2: echo "<td><span class='label label-warning'>" . lang($requests_item['status_name']) . "</span></td>"; break;
            case 3: echo "<td><span class='label label-success'>" . lang($requests_item['status_name']) . "</span></td>"; break;
            default: echo "<td><span class='label label-important' style='background-color: #ff0000;'>" . lang($requests_item['status_name']) . "</span></td>"; break;
        }?>
        <?php
        if ($this->config->item('enable_history') == TRUE){
          echo "<td data-order='".$tmpRequestDate."'>" . $requestdate . "</td>";
          echo "<td data-order='".$tmpLastChangeDate."'>" . $lastchangedate . "</td>";
        }
        ?>
    </tr>
<?php endforeach ?>
	</tbody>
</table>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo base_url();?>requests/export/<?php echo $filter; ?>" class="btn btn-primary"><i class="fa fa-file-excel-o"></i>&nbsp; <?php echo lang('requests_index_button_export');?></a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url();?>requests/all" class="btn btn-primary"><i class="icon-filter icon-white"></i>&nbsp; <?php echo lang('requests_index_button_show_all');?></a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url();?>requests/requested" class="btn btn-primary"><i class="icon-filter icon-white"></i>&nbsp; <?php echo lang('requests_index_button_show_pending');?></a>
        &nbsp;&nbsp;
        <?php if ($this->config->item('ics_enabled') == TRUE) {?>
        <a id="lnkICS" href="#"><i class="icon-globe"></i> ICS</a>
        <?php }?>
    </div>
</div>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>

<div id="frmShowHistory" class="modal hide fade">
    <div class="modal-body" id="frmShowHistoryBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmShowHistory').modal('hide');" class="btn"><?php echo lang('OK');?></a>
    </div>
</div>

<div id="frmLinkICS" class="modal hide fade">
    <div class="modal-header">
        <h3>ICS<a href="#" onclick="$('#frmLinkICS').modal('hide');" class="close">&times;</a></h3>
    </div>
    <div class="modal-body" id="frmSelectDelegateBody">
        <div class='input-append'>
                <input type="text" class="input-xlarge" id="txtIcsUrl" onfocus="this.select();" onmouseup="return false;"
                    value="<?php echo base_url() . 'ics/collaborators/' . $user_id;?>" />
                 <button id="cmdCopy" class="btn" data-clipboard-text="<?php echo base_url() . 'ics/collaborators/' . $user_id;?>">
                     <i class="fa fa-clipboard"></i>
                 </button>
                <a href="#" id="tipCopied" data-toggle="tooltip" title="<?php echo lang('copied');?>" data-placement="right" data-container="#cmdCopy"></a>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" onclick="$('#frmLinkICS').modal('hide');" class="btn btn-primary"><?php echo lang('OK');?></a>
    </div>
</div>

<link href="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/datatable/DataTables-1.10.11/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/clipboard-1.6.1.min.js"></script>

<script type="text/javascript">
var clicked = false;
var leaveTable = null;

//Return a URL parameter identified by 'name'
function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}

//Apply a filter on the status column
function filterStatusColumn() {
    var filter = "^(";
    if ($('#chkPlanned').prop('checked')) filter += "<?php echo lang('Planned');?>|";
    if ($('#chkAccepted').prop('checked')) filter += "<?php echo lang('Accepted');?>|";
    if ($('#chkRequested').prop('checked')) filter += "<?php echo lang('Requested');?>|";
    if ($('#chkRejected').prop('checked')) filter += "<?php echo lang('Rejected');?>|";
    if ($('#chkCancellation').prop('checked')) filter += "<?php echo lang('Cancellation');?>|";
    if ($('#chkCanceled').prop('checked')) filter += "<?php echo lang('Canceled');?>|";
    filter = filter.slice(0,-1) + ")$";
    if (filter.indexOf('(') == -1) filter = 'nothing is selected';
    leaveTable.columns( 6 ).search( filter, true, false ).draw();
}

$(document).ready(function() {
    //Transform the HTML table in a fancy datatable
    leaveTable = $('#leaves').DataTable({
            order: [[ 2, "desc" ]],
            language: {
                decimal:            "<?php echo lang('datatable_sInfoThousands');?>",
                processing:       "<?php echo lang('datatable_sProcessing');?>",
                search:              "<?php echo lang('datatable_sSearch');?>",
                lengthMenu:     "<?php echo lang('datatable_sLengthMenu');?>",
                info:                   "<?php echo lang('datatable_sInfo');?>",
                infoEmpty:          "<?php echo lang('datatable_sInfoEmpty');?>",
                infoFiltered:       "<?php echo lang('datatable_sInfoFiltered');?>",
                infoPostFix:        "<?php echo lang('datatable_sInfoPostFix');?>",
                loadingRecords: "<?php echo lang('datatable_sLoadingRecords');?>",
                zeroRecords:    "<?php echo lang('datatable_sZeroRecords');?>",
                emptyTable:     "<?php echo lang('datatable_sEmptyTable');?>",
                paginate: {
                    first:          "<?php echo lang('datatable_sFirst');?>",
                    previous:   "<?php echo lang('datatable_sPrevious');?>",
                    next:           "<?php echo lang('datatable_sNext');?>",
                    last:           "<?php echo lang('datatable_sLast');?>"
                },
                aria: {
                    sortAscending:  "<?php echo lang('datatable_sSortAscending');?>",
                    sortDescending: "<?php echo lang('datatable_sSortDescending');?>"
                }
            }
        });

     //Prevent double click on accept and reject buttons
     $('#leaves').on('click', '.lnkAccept', function (event) {
        event.preventDefault();
        if (!clicked) {
            clicked = true;
            window.location.href = "<?php echo base_url();?>requests/accept/" + $(this).data("id");
        }
     });
     $("#leaves").on('click', '.lnkReject', function (event) {
        event.preventDefault();
        if (!clicked) {
            clicked = true;
            window.location.href = "<?php echo base_url();?>requests/reject/" + $(this).data("id");
        }
     });

    <?php if ($this->config->item('enable_history') === TRUE) { ?>
    //Prevent to load always the same content (refreshed each time)
    $('#frmShowHistory').on('hidden', function() {
        $("#frmShowHistoryBody").html('<img src="<?php echo base_url();?>assets/images/loading.gif">');
    });

    //Popup show history
    $("#leaves tbody").on('click', '.show-history',  function(){
        $("#frmShowHistory").modal('show');
        $("#frmShowHistoryBody").load('<?php echo base_url();?>leaves/' + $(this).data('id') +'/history');
    });
    <?php } ?>

    //Copy/Paste ICS Feed
    var client = new Clipboard("#cmdCopy");
    $('#lnkICS').click(function () {
        $("#frmLinkICS").modal('show');
    });
    client.on( "success", function() {
        $('#tipCopied').tooltip('show');
        setTimeout(function() {$('#tipCopied').tooltip('hide')}, 1000);
    });

    $('#cboLeaveType').on('change',function(){
        var leaveType = $("#cboLeaveType option:selected").text();
        if (leaveType != '') {
            leaveTable.columns( 5 ).search( "^" + leaveType + "$", true, false ).draw();
        } else {
            leaveTable.columns( 5 ).search( "", true, false ).draw();
        }
    });

    //Analyze URL to get the filter on one type
    if (getURLParameter('type') != null) {
        var leaveType = $("#cboLeaveType option[value='" + getURLParameter('type') + "']").text();
        $("#cboLeaveType option[value='" + getURLParameter('type') + "']").prop("selected", true);
        leaveTable.columns( 5 ).search( "^" + leaveType + "$", true, false ).draw();
    }

    //Filter on statuses is a list of inclusion
    var statuses = getURLParameter('statuses');
    if (statuses != null) {
        //Unselect all statuses and select only the statuses passed by URL
        $(".filterStatus").prop("checked", false);
        statuses.split(/\|/).forEach(function(status) {
            switch (status) {
                case '1': $("#chkPlanned").prop("checked", true); break;
                case '2': $("#chkRequested").prop("checked", true); break;
                case '3': $("#chkAccepted").prop("checked", true); break;
                case '4': $("#chkRejected").prop("checked", true); break;
                case '5': $("#chkCancellation").prop("checked", true); break;
                case '6': $("#chkCanceled").prop("checked", true); break;
            }
        });
        //$("#cboLeaveType option[value='" + getURLParameter('type') + "']").prop("selected", true);
        filterStatusColumn();
    }
    $('.filterStatus').on('change',function(){
        filterStatusColumn();
    });
});
</script>
