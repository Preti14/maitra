<div class="box col-md-12">
<div class="box-inner">
<div class="box-header well" data-original-title="">
  <h2><i class=""></i> Plugins List		
  </h2>
<div class="box-icon"> <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a> </div>
</div>




<div class="box-content">

<form id="distributedtable" method="post" action="">

<div class="col-md-4">


<h4 align="right">
<strong>
</strong></h4></div>
<div class="clearfix"></div>

<table  id="distributed_list" width="100%" class="table table-striped add-style table-bordered bootstrap-datatable datatable responsive">

  <thead>
    <tr>
<th class="no-print" style="width:7%">S.No</th>
      <th style="width:9%">Plugin name</th>
      <th style="width:8%">Description</th>
      <th style="width:8%">Action</th>
    </tr>
  </thead>
  <tbody>
    
      <td>1</td>
      <td><?php echo  $plugins_list["plugin_name"];?></td>
      <td><?php echo  $plugins_list["plugin_description"];?></td>
     <td><?php  echo ($plugins_list["active"] == 0)? "<a href='?c=home&m=activate'>Activate</a>": "<a href='?c=home&m=deactivate'>Deactivate</a>";?></td>
    </tr>
   
    
  </tbody>
</table>



<div class="clearfix"></div>
</div>
</div>
</div>
