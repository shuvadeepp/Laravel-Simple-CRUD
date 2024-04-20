<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ADD PAGE</title>
</head>
<style>
  .color-animation { animation: colorChange 3s infinite alternate; } 
  @keyframes colorChange { 0% { color: red; } 25% { color: blue; } 50% { color: green; } 75% { color: orange;
    } 100% { color: purple; } }
</style>
<?php $url = url('/PersonalAssessment/crud_pratice_19042024/'); ?>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-md-7 col-lg-8">
                
                    <br>
                    <br>
                    <h4 class="mb-3 color-animation" style="text-align: center;">Job Portal Apply Application Form</h4>
                    <br>
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <br><br>
                    <form class="needs-validation" method="POST" novalidate="">
                        @csrf
                        <div class="row g-3">
                        <h4 class="mb-3">User Details</h4>
                            <div class="col-12">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" > 
                                </div>
                                    <span class="errMsg_username errDiv" style="color: red;"></span>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">Email </label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="you@example.com"> 
                                <span class="errMsg_email errDiv" style="color: red;"></span> 
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" id="address" placeholder="1234 Main St" required=""> 
                                <span class="errMsg_address errDiv" style="color: red;"></span> 
                            </div>


                            <hr class="my-4">

                            <h4 class="mb-3">Select State</h4>

                            <div class="col-md-5">
                                <label for="State" class="form-label"> State </label>
                                <select class="form-select" name="State" id="State" >
                                    <option value="">--Choose--</option> 
                                    @if(!empty($arrAllRecords))
                                    @foreach($arrAllRecords as $stateAllRecord)
                                        <option value="{{ $stateAllRecord->state_id }}" > {{ $stateAllRecord->states_name }} </option>
                                    @endforeach
                                    @endif
                                </select> 
                                <span class="errMsg_State errDiv" style="color: red;"></span> 
                            </div>

                            <div class="col-md-4">
                                <label for="city" class="form-label"> City </label>
                                <select class="form-select" name="city" id="city">
                                    <option value="">--Choose--</option>  
                                </select> 
                                <span class="errMsg_city errDiv" style="color: red;"></span> 
                            </div>

                            <div class="col-md-3">
                                <label for="zip" class="form-label">Zip</label>
                                <input type="text" class="form-control" name="zip" id="zip" placeholder="" maxlength="6"> 
                                <span class="errMsg_zip errDiv" style="color: red;"></span> 
                            </div>
                        </div> 
                        
                        <hr class="my-4">

                        <h4 class="mb-3">Gender</h4>

                        <div class="my-3">
                            <div class="form-check">
                                <input id="male" name="intGender" type="radio" class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check">
                                <input id="female" name="intGender" type="radio" class="form-check-input" value="2">
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button class="w-100 btn btn-primary btn-lg" type="submit" onclick="return validator()"> Send </button>
                        <br>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
<script>
    var SITE_URL = '{{ $url }}';
    // alert(SITE_URL);
    $(document).ready(function() {
        $('#State').change(function() {
            var stateData = $(this).val(); 
            var stateType = 0;
                $.ajax({  
                    url: SITE_URL + "/Manage/cityData",
                    method: 'post',
                    dataType : "json",
                    data:{"_token": "{{ csrf_token() }}", stateData: stateData, stateType: stateType},
                    success: function(resp) {
                        // console.log(resp.city_resp);
                          $('#city').html(resp.city_resp);
                    }
                });
        });
    });
    function validator() {
      $('.errDiv').hide(); 

        if (!blankCheck('username', 'Please enter your User Name'))
            return false;
        if (!blankCheck('email', 'Please enter your Email ID'))
            return false;
        if (!blankCheck('address', 'Please enter your Address'))
            return false;
        if (!blankCheck('State', 'Please Select State'))
            return false;
        if (!blankCheck('city', 'Please Select City'))
            return false;
        if (!blankCheck('zip', 'Please Select zip Code'))
            return false;

    //   $('#confirmAlertModal').modal('show');
    //   $('#btnConfirmModalOK').on('click',function(){
    //           $('#listform').submit(); 
    //   });
    }

    function blankCheck(controlId,msg) {
        if($('#'+controlId).val() == '') {  
            $('.errMsg_'+ controlId).html(msg).show();
            $('#'+ controlId).addClass('error-input');
            $('#'+ controlId).focus();
            return false;
        }
	    return true;
    }
</script>
</html>