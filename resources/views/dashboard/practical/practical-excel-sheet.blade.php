@extends('dashboard.base')

@section('content')

@if(session('message'))
  <div class="flash-message" id="flash-message" data-expires="5000" style="padding: 2rem;">
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{session('message')}}</p>
  </div> <!-- end .flash-message -->    
@endif
@if($errors->any())
  <div class="flash-message" id="flash-message" data-expires="5000" style="padding: 2rem;">
    <p class="alert alert-danger">
      {!! implode('', $errors->all(':message')) !!}
    </p>
  </div>
@endif

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('Practical Input Output Excel sheet') }}</div>
                    <div class="card-body">
                        
                        <br>
                        <form method="GET" action="{{ url('/practical/practical/' . $practical->id . '/excel-sheet/download') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary mt-2 ml-1">
                                <i class="cil-cloud-download"></i>
                                <i class="cil-file"></i>
                                Download template Excel file
                            </button> 
                        </form>

                          <form method="POST" action="{{ url('/practical/practical/' . $practical->id . '/excel-sheet/upload') }}" id='file-file-form' enctype="multipart/form-data">
                            @csrf
                            
                            <label class="btn btn-primary mt-2 ml-1">
                                <i class="cil-cloud-upload"></i>
                                <i class="cil-file"></i>
                                Upload Excel file <input type="file" name="file" id="file-file-input" hidden>
                            </label> 
                        </form>
                        <a href="{{ route('practical.index') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
  setTimeout(function() {
      $('#flash-message').fadeOut('fast');
  }, 3000);

//   $( document ).ready(function() {
//     populateDepartmentSelect('department-select', $("#university-select").val());
//   });

  $('#university-select').change(function () {
      var id = $(this).val();
      console.log("Called");
      populateDepartmentSelect("department-select", id);
  });

  $('#department-select').change(function () {
      var id = $(this).val();
      console.log("Called");
      populateSubjectSelect("subject-select", id);
  });

  function populateDepartmentSelect(departmentSelectId, universityId) {
    $.ajax({
      url: '{{route("university.getalldepartments") }}',
      type: 'POST',
      data: {
        "university_id": universityId,
        "_token": '{{ csrf_token() }}',
      },
      success: function(result){
        let htmlString = "";
        if(result.status != 200) return;
        let data = result.data;
        for(let i = 0; i < data.length; i++) {
          htmlString += `<option value='${data[i].id}'>${data[i].name}</option>`;
        }
        console.log(htmlString);
        $(`#${departmentSelectId}`).html(htmlString);
        selectFromDropDown(departmentSelectId, data[0].name);
        populateSubjectSelect('subject-select', $("#department-select").val());
      },
    });
  }

  function populateSubjectSelect(subjectSelectId, departmentId) {
    $.ajax({
      url: '{{route("department.getallsubjects") }}',
      type: 'POST',
      data: {
        "department_id": departmentId,
        "_token": '{{ csrf_token() }}',
      },
      success: function(result){
        let htmlString = "";
        if(result.status != 200) return;
        let data = result.data;
        for(let i = 0; i < data.length; i++) {
          htmlString += `<option value='${data[i].id}'>${data[i].name}</option>`;
        }
        console.log(htmlString);
        $(`#${subjectSelectId}`).html(htmlString);
        selectFromDropDown(subjectSelectId, data[0].name);
      },
    });
  }

  function selectFromDropDown(departmentSelectId, valueToSet) {
    let selectObj = document.getElementById(departmentSelectId);
    // let valueToSet = $(this).attr('data-department');
    for (var i = 0; i < selectObj.options.length; i++) {
      if (selectObj.options[i].text== valueToSet) {
          selectObj.options[i].selected = true;
          break;
      }
    }
  }

  document.getElementById('file-file-input').onchange = function() {
        document.getElementById('file-file-form').submit();
    };
 
</script>
@endsection

