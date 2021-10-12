@extends('dashboard.base')

@section('content')

@if(session('message'))
  <div class="flash-message" id="flash-message" data-expires="5000" style="padding: 2rem;">
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{session('message')}}</p>
  </div> <!-- end .flash-message -->    
  @endif

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('Practical') }}</div>
                    <div class="card-body">
                        <div class="row"> 
                          <a href="{{ route('practical.create') }}" class="btn btn-primary m-2">{{ __('Add Practical') }}</a>
                        </div>
                        <br>
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Sr. no.</th>
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Department</th>
                            <th>University</th>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $index = $practicals->perPage() * ($practicals->currentPage() - 1) + 1; ?>
                          @foreach($practicals as $practical)
                            <tr>
                              <td>{{ $index++ }}</td>
                              <td><strong>{{ $practical->title }}</strong></td>
                              <td>{{ $practical->subject->name }}</td>
                              <td>{{ $practical->subject->department->name }}</td>
                              <td>{{ $practical->subject->department->university->name }}</td>
                              <td>
                                <a href="{{ url('/practical/practical/' . $practical->id . '/excel-sheet') }}" class="btn btn-block btn-warning">Excel sheet</a>
                              </td>
                              <td>
                                <a href="{{ url('/practical/practical/' . $practical->id) }}" class="btn btn-block btn-info">View</a>
                              </td>
                              <td>
                                <a href="{{ url('/practical/practical/' . $practical->id . '/edit') }}" class="btn btn-block btn-primary">Edit</a>
                              </td>
                              
                              <td>
                                <form action="{{ route('practical.destroy', $practical->id ) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger">Delete</button>
                                </form>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $practicals->links() }}
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
 
</script>
@endsection

