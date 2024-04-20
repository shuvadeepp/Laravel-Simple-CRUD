<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <title>VIEW PAGE</title>
   </head>
   <?php $url = url('/personalAssessment/crud_pratice_19042024/'); ?>
   <body>
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-11"><br><br>
                <h4 style="text-align: center;">List Of Job Application</h4><br>
                <a href="<?php echo $url . '/Manage'; ?>">back</a>
               <table id="dataTable" class="table table-striped table table-bordered" style="width:100%">
                  <thead>
                     <tr>
                        <th scope="col">Sl#</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Email ID</th>
                        <th scope="col">Address</th>
                        <th scope="col">ZIP</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Selected State & city</th>
                        <th scope="col">Submitted Form At</th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php //echo'<pre>';print_r($arrAllRecords);exit; ?>
                    <?php if(!empty($arrAllRecords)){ $i=1; ?>
                    <?php foreach($arrAllRecords as $recordData){ ?>
                     <tr>
                        <th scope="row"><?php echo $i++; ?></th>
                        <td> <?php echo $recordData['vchUsername'] ?> </td> 
                        <td> <?php echo $recordData['vchEmail_ID'] ?> </td> 
                        <td> <?php echo $recordData['vchAddress'] ?> </td> 
                        <td> <?php echo $recordData['intZip'] ?> </td> 
                        <td> <?php echo (($recordData['intGender'] == 1) ? " Male " : " Female " ) ?> </td> 
                        <td> <?php echo $recordData['states_name'] . ', ' . $recordData['city_name'] ?> </td> 
                        <td><span class="badge text-bg-success"> <?php echo date('d-m-Y', strtotime($recordData['created_at'])) ?> </span></td> 
                     </tr>  
                     <?php }} else { ?>
                        <tr><td colspan="8" style="text-align: center; font-weight: bold;"> No Record Found!!! </td> </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </body>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</html>